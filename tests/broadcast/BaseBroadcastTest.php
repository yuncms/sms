<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\tests\broadcast;

use Yii;
use yii\helpers\FileHelper;
use yuncms\broadcast\BaseMessage;
use yuncms\broadcast\BaseBroadcast;
use yuncms\tests\TestCase;

class BaseBroadcastTest extends TestCase
{
    public function setUp()
    {
        $this->mockApplication([
            'components' => [
                'broadcast' => $this->createTestBroadcastComponent(),
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
     * @return Broadcast test broadcast component instance.
     */
    protected function createTestBroadcastComponent()
    {
        $component = new Broadcast();
        return $component;
    }

    public function testUseFileTransport()
    {
        $broadcast = new Broadcast();
        $this->assertFalse($broadcast->useFileTransport);
        $this->assertEquals('@runtime/broadcast', $broadcast->fileTransportPath);
        $broadcast->fileTransportPath = '@yuncms/tests/runtime/broadcast';
        $broadcast->useFileTransport = true;
        $broadcast->fileTransportCallback = function () {
            return 'message.txt';
        };
        $message = $broadcast->createMessage([]);
        $this->assertTrue($broadcast->send($message));
        $file = Yii::getAlias($broadcast->fileTransportPath) . '/message.txt';
        $this->assertTrue(is_file($file));
        $this->assertStringEqualsFile($file, $message->toString());
    }

    public function testBeforeSendEvent()
    {
        $message = new Message();
        $broadcastMock = $this->getMockBuilder('yuncms\tests\broadcast\Broadcast')
            ->setMethods(['beforeSend', 'afterSend'])
            ->getMock();
        $broadcastMock->expects($this->once())->method('beforeSend')->with($message)->will($this->returnValue(true));
        $broadcastMock->expects($this->once())->method('afterSend')->with($message, true);
        $broadcastMock->send($message);
    }
}

/**
 * Test Mailer class.
 */
class Broadcast extends BaseBroadcast
{
    public $messageClass = 'yuncms\tests\broadcast\Message';

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