<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\mq;

use Yii;
use yii\base\Component;

/**
 * 消息队列组件
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
abstract class BaseMessageQueue extends Component implements MessageQueueInterface
{
    /**
     * @event MessageQueueEvent an event raised right before send.
     * You may set [[MessageQueueEvent::isValid]] to be false to cancel the send.
     */
    const EVENT_BEFORE_SEND = 'beforeSend';

    /**
     * @event MessageQueueEvent an event raised right after send.
     */
    const EVENT_AFTER_SEND = 'afterSend';

    /**
     * @var string the default class name of the new message instances created by [[createMessage()]]
     */
    public $messageClass = 'yuncms\mq\BaseMessage';

    /**
     * @var bool whether to save message queue messages as files under [[fileTransportPath]] instead of sending them
     * to the actual recipients. This is usually used during development for debugging purpose.
     * @see fileTransportPath
     */
    public $useFileTransport = false;

    /**
     * @var string the directory where the message queue messages are saved when [[useFileTransport]] is true.
     */
    public $fileTransportPath = '@runtime/mq';

    /**
     * @var callable a PHP callback that will be called by [[send()]] when [[useFileTransport]] is true.
     * The callback should return a file name which will be used to save the message queue message.
     * If not set, the file name will be generated based on the current timestamp.
     *
     * The signature of the callback is:
     *
     * ```php
     * function ($messageQueue, $message)
     * ```
     */
    public $fileTransportCallback;

    /**
     * Creates a new message instance and optionally composes its body content via view rendering.
     *
     * @param array|object $message message payload.
     * @param string|array|null $tag message tag
     * @param array $attributes
     * @return MessageInterface|object message instance.
     * @throws \yii\base\InvalidConfigException
     */
    public function createMessage($message, $tag = null, array $attributes = []): MessageInterface
    {
        $config = [
            'class' => $this->messageClass,
            'messageQueue' => $this,
            'body' => $message,
            'tag' => $tag,
            'attributes' => $attributes,
        ];
        return Yii::createObject($config);
    }

    /**
     * 快速推送一条消息
     * @param array|object $message
     * @param string|array|null $tag
     * @param array|null $attributes
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function PublishMessage($message, $tag = null, $attributes = null)
    {
        return $this->createMessage($message, $tag, $attributes)->send();
    }

    /**
     * Sends the given message queue message.
     * This method will log a message about the message queue being sent.
     * If [[useFileTransport]] is true, it will save the message queue as a file under [[fileTransportPath]].
     * Otherwise, it will call [[sendMessage()]] to send the message queue to its recipient(s).
     * Child classes should implement [[sendMessage()]] with the actual message queue sending logic.
     * @param MessageInterface $message message queue message instance to be sent
     * @return bool whether the message has been sent successfully
     */
    public function send($message)
    {
        if (!$this->beforeSend($message)) {
            return false;
        }
        Yii::info('Sending message queue :' . $message->toJson(), __METHOD__);

        if ($this->useFileTransport) {
            $isSuccessful = $this->saveMessage($message);
        } else {
            $isSuccessful = $this->sendMessage($message);
        }
        $this->afterSend($message, $isSuccessful);

        return $isSuccessful;
    }

    /**
     * Sends multiple messages at once.
     *
     * The default implementation simply calls [[send()]] multiple times.
     * Child classes may override this method to implement more efficient way of
     * sending multiple messages.
     *
     * @param array $messages list of message queue messages, which should be sent.
     * @return int number of messages that are successfully sent.
     */
    public function sendMultiple(array $messages)
    {
        $successCount = 0;
        foreach ($messages as $message) {
            if ($this->send($message)) {
                $successCount++;
            }
        }

        return $successCount;
    }

    /**
     * Sends the specified message.
     * This method should be implemented by child classes with the actual message queue sending logic.
     * @param MessageInterface $message the message to be sent
     * @return bool whether the message is sent successfully
     */
    abstract protected function sendMessage($message);

    /**
     * Saves the message as a file under [[fileTransportPath]].
     * @param MessageInterface $message
     * @return bool whether the message is saved successfully
     */
    protected function saveMessage($message)
    {
        $path = Yii::getAlias($this->fileTransportPath);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        if ($this->fileTransportCallback !== null) {
            $file = $path . '/' . call_user_func($this->fileTransportCallback, $this, $message);
        } else {
            $file = $path . '/' . $this->generateMessageFileName();
        }
        file_put_contents($file, $message->toJson());
        return true;
    }

    /**
     * @return string the file name for saving the message when [[useFileTransport]] is true.
     */
    public function generateMessageFileName()
    {
        $time = microtime(true);
        return date('Ymd-His-', $time) . sprintf('%04d', (int)(($time - (int)$time) * 10000)) . '-' . sprintf('%04d', mt_rand(0, 10000)) . '.eml';
    }

    /**
     * This method is invoked right before mail send.
     * You may override this method to do last-minute preparation for the message.
     * If you override this method, please make sure you call the parent implementation first.
     * @param MessageInterface $message
     * @return bool whether to continue sending an message queue.
     */
    public function beforeSend($message)
    {
        $event = new MessageQueueEvent(['message' => $message]);
        $this->trigger(self::EVENT_BEFORE_SEND, $event);
        return $event->isValid;
    }

    /**
     * This method is invoked right after mail was send.
     * You may override this method to do some postprocessing or logging based on mail send status.
     * If you override this method, please make sure you call the parent implementation first.
     * @param MessageInterface $message
     * @param bool $isSuccessful
     */
    public function afterSend($message, $isSuccessful)
    {
        $event = new MessageQueueEvent(['message' => $message, 'isSuccessful' => $isSuccessful]);
        $this->trigger(self::EVENT_AFTER_SEND, $event);
    }
}