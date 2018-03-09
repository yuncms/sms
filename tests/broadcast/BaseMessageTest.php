<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\broadcast;

use Yii;
use yuncms\broadcast\BaseBroadcast;
use yuncms\broadcast\BaseMessage;
use yuncms\tests\TestCase;

class BaseMessageTest extends TestCase
{
    public function setUp()
    {
        $this->mockApplication([
            'components' => [
                'broadcast' => $this->createTestBroadcastComponent(),
            ],
        ]);
    }
    /**
     * @return Broadcast test broadcast component instance.
     */
    protected function createTestBroadcastComponent()
    {
        $component = new TestBroadcast();
        return $component;
    }
    /**
     * @return TestBroadcast broadcast instance.
     */
    protected function getBroadcast()
    {
        return Yii::$app->get('broadcast');
    }

    // Tests :
    public function testSend()
    {
        $broadcast = $this->getBroadcast();
        $message = $broadcast->createMessage([]);
        $message->send($broadcast);
        $this->assertEquals($message, $broadcast->sentMessages[0], 'Unable to send message!');
    }
    public function testToString()
    {
        $broadcast = $this->getBroadcast();
        $message = $broadcast->createMessage([]);
        $this->assertEquals($message->toString(), '' . $message);
    }
}

/**
 * Test Broadcast class.
 */
class TestBroadcast extends BaseBroadcast
{
    public $messageClass = 'yuncms\tests\broadcast\TestMessage';
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