<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\broadcast\aliyun;

use Yii;
use yii\base\InvalidConfigException;
use yuncms\broadcast\BaseBroadcast;
use yuncms\broadcast\MessageInterface;
use AliyunMNS\Config;
use AliyunMNS\Http\HttpClient;
use AliyunMNS\Requests\PublishMessageRequest;
use AliyunMNS\Responses\PublishMessageResponse;

/**
 * Class Broadcast
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Broadcast extends BaseBroadcast
{

    /**
     * @var  string
     */
    public $endPoint;

    /**
     * @var string
     */
    public $accessId;

    /**
     * @var string
     */
    public $accessKey;

    /**
     * @var null|string
     */
    public $securityToken = null;

    /**
     * @var  string 主题名称
     */
    public $topicName;

    /**
     * @var null|Config
     */
    public $config = null;

    /**
     * @var HttpClient
     */
    protected $_client;

    /**
     * 初始化组件
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty ($this->endPoint)) {
            throw new InvalidConfigException ('The "endPoint" property must be set.');
        }
        if (empty ($this->accessId)) {
            throw new InvalidConfigException ('The "accessId" property must be set.');
        }
        if (empty ($this->accessKey)) {
            throw new InvalidConfigException ('The "accessKey" property must be set.');
        }
        if (empty ($this->topicName)) {
            throw new InvalidConfigException ('The "topicName" property must be set.');
        }
    }

    /**
     * @return array|HttpClient aliyun mns topic instance or array configuration.
     * @throws InvalidConfigException
     */
    public function getClient()
    {
        if (!is_object($this->_client)) {
            $this->_client = Yii::createObject('AliyunMNS\Http\HttpClient', [
                $this->endPoint,
                $this->accessId,
                $this->accessKey,
                $this->securityToken,
                $this->config
            ]);
        }
        return $this->_client;
    }

    /**
     * Sends the specified message.
     * This method should be implemented by child classes with the actual broadcast sending logic.
     * @param MessageInterface $message the message to be sent
     * @return bool whether the message is sent successfully
     * @throws InvalidConfigException
     */
    protected function sendMessage($message)
    {
        $request = new PublishMessageRequest($message->toString(), $message->getTag(), $message->getAttributes());
        $request->setTopicName($this->topicName);
        $response = new PublishMessageResponse();
        $this->getClient()->sendRequest($request, $response);
        return $response->isSucceed();
    }
}