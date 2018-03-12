<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\mq;

use Yii;
use yuncms\mq\BaseMessageQueue;
use yuncms\mq\BaseMessage;
use yuncms\tests\TestCase;


class BaseMessageTest extends TestCase
{
    public function setUp()
    {
        $this->mockApplication([
            'components' => [
                'messageQueue' => $this->createTestMessageQueueComponent(),
            ],
        ]);
    }

    /**
     * @return TestMessageQueue test message queue component instance.
     */
    protected function createTestMessageQueueComponent()
    {
        $component = new TestMessageQueue();
        return $component;
    }
    /**
     * @return TestMessageQueue broadcast instance.
     */
    protected function getMessageQueue()
    {
        return Yii::$app->get('messageQueue');
    }

    // Tests :
    public function testSend()
    {
        $broadcast = $this->getMessageQueue();
        $message = $broadcast->createMessage([]);
        $message->send($broadcast);
        $this->assertEquals($message, $broadcast->sentMessages[0], 'Unable to send message!');
    }
    public function testToJson()
    {
        $broadcast = $this->getMessageQueue();
        $message = $broadcast->createMessage([]);
        $this->assertEquals($message->toJson(), '' . $message);
    }
}

/**
 * Test Broadcast class.
 */
class TestMessageQueue extends BaseMessageQueue
{
    public $messageClass = 'yuncms\tests\mq\TestMessage';
    public $sentMessages = [];
    protected function sendMessage($message)
    {
        $this->sentMessages[] = $message;
    }
}
/**
 * Test Message class.
 */
class TestMessage extends BaseMessage
{


    public function toString()
    {
        return get_class($this);
    }
}