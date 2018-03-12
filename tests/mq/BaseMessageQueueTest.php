<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\mq;

use Yii;
use yii\helpers\FileHelper;
use yuncms\mq\BaseMessage;
use yuncms\mq\BaseMessageQueue;
use yuncms\tests\TestCase;

class BaseMessageQueueTest extends TestCase
{
    public function setUp()
    {
        $this->mockApplication([
            'components' => [
                'mq' => $this->createTestMessageQueueComponent(),
            ],
        ]);
        $filePath = $this->getTestFilePath();
        if (!file_exists($filePath)) {
            FileHelper::createDirectory($filePath);
        }
    }

    public function tearDown()
    {
        $filePath = $this->getTestFilePath();
        if (file_exists($filePath)) {
            FileHelper::removeDirectory($filePath);
        }
    }
    /**
     * @return string test file path.
     */
    protected function getTestFilePath()
    {
        return Yii::getAlias('@yuncms/tests/runtime') . DIRECTORY_SEPARATOR . basename(get_class($this)) . '_' . getmypid();
    }

    /**
     * @return MessageQueue test broadcast component instance.
     */
    protected function createTestMessageQueueComponent()
    {
        $component = new MessageQueue();
        return $component;
    }

    public function testUseFileTransport()
    {
        $broadcast = new MessageQueue();
        $this->assertFalse($broadcast->useFileTransport);
        $this->assertEquals('@runtime/mq', $broadcast->fileTransportPath);
        $broadcast->fileTransportPath = '@yuncms/tests/runtime/mq';
        $broadcast->useFileTransport = true;
        $broadcast->fileTransportCallback = function () {
            return 'message.txt';
        };
        $message = $broadcast->createMessage([]);
        $this->assertTrue($broadcast->send($message));
        $file = Yii::getAlias($broadcast->fileTransportPath) . '/message.txt';
        $this->assertTrue(is_file($file));
        $this->assertStringEqualsFile($file, $message->toJson());
    }

    public function testBeforeSendEvent()
    {
        $message = new Message();
        $messageQueueMock = $this->getMockBuilder('yuncms\tests\mq\MessageQueue')
            ->setMethods(['beforeSend', 'afterSend'])
            ->getMock();
        $messageQueueMock->expects($this->once())->method('beforeSend')->with($message)->will($this->returnValue(true));
        $messageQueueMock->expects($this->once())->method('afterSend')->with($message, true);
        $messageQueueMock->send($message);
    }
}

/**
 * Test MessageQueue class.
 */
class MessageQueue extends BaseMessageQueue
{
    public $messageClass = 'yuncms\tests\mq\Message';

    public $sentMessages = [];

    protected function sendMessage($message)
    {
        $this->sentMessages[] = $message;
        return true;
    }
}

/**
 * Test Message class.
 */
class Message extends BaseMessage
{
    public function toString()
    {
        $s = var_export($this, true);
        return $s;
    }
}