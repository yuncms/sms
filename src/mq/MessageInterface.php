<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\mq;

/**
 * MessageInterface is the interface that should be implemented by message queue message classes.
 *
 * A message represents the settings and content of an message queue, such as the sender, recipient,
 * subject, body, etc.
 *
 * Messages are sent by a [[\yuncms\mq\MessageQueueInterface|messageQueue]], like the following,
 *
 * ```php
 * Yii::$app->mq->compose(['aaa'=>'bbb'])
 *     ->send();
 * ```
 *
 * @see MessageQueueInterface
 * @package yuncms\mq
 */
interface MessageInterface
{
    /**
     * Sends this email message.
     * @param MessageQueueInterface $messageQueue the message queue that should be used to send this message.
     * If null, the "messageQueue" application component will be used instead.
     * @return bool whether this message is sent successfully.
     */
    public function send(MessageQueueInterface $messageQueue = null);

    public function getTag();

    public function getAttributes();

    public function getBody();

    /**
     * Returns string representation of this message.
     * @return string the string representation of this message.
     */
    public function toJson();
}