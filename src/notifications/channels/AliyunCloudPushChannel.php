<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\channels;

use Yii;
use yii\base\Component;
use yii\di\Instance;
use yuncms\notifications\contracts\ChannelInterface;
use yuncms\notifications\contracts\NotifiableInterface;
use yuncms\notifications\contracts\NotificationInterface;
use yuncms\notifications\messages\AppMessage;

/**
 * App 推送渠道
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class AliyunCloudPushChannel extends Component implements ChannelInterface
{
    /**
     * @var string|\xutl\aliyun\Aliyun
     */
    public $aliyun;

    /**
     * @var string
     */
    public $appKey;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->aliyun = Instance::ensure($this->aliyun, 'xutl\aliyun\Aliyun');
    }

    /**
     * @param NotifiableInterface $recipient
     * @param NotificationInterface $notification
     */
    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        /**
         * @var $message AppMessage
         */
        $message = $notification->exportFor('app');
        $appRecipient = $recipient->routeNotificationFor('app');
        $this->aliyun->getCloudPush()->push([
            'AppKey' => $this->appKey,
            'Target' => $appRecipient->target,
            'TargetValue' => $appRecipient->targetValue,
            'DeviceType' => 'ALL',
            'Title' => $message->title,
            'PushType' => 'NOTICE',//表示通知
            'Body' => $message->body,
            'StoreOffline' => 'true',
            'ExpireTime' => '',
            'ExtParameters' => $message->extParameters,//JSON
        ]);
    }
}