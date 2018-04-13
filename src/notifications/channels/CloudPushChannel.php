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
use yuncms\helpers\Json;
use yuncms\notifications\contracts\ChannelInterface;
use yuncms\notifications\contracts\RecipientInterface;
use yuncms\notifications\contracts\NotificationInterface;
use yuncms\notifications\messages\CloudPushMessage;
use xutl\aliyun\Aliyun;

/**
 * App 推送渠道
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class CloudPushChannel extends Component implements ChannelInterface
{
    /**
     * @var string|Aliyun
     */
    public $aliyun = 'aliyun';

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
        $this->aliyun = Instance::ensure($this->aliyun, Aliyun::class);
    }

    /**
     * @param RecipientInterface $recipient
     * @param NotificationInterface $notification
     */
    public function send(RecipientInterface $recipient, NotificationInterface $notification)
    {
        /**
         * @var $message CloudPushMessage
         */
        $message = $notification->exportFor('aliyunCloudPush');
        $appRecipient = $recipient->routeNotificationFor('aliyunCloudPush');
        if ($message->validate()) {
            $messageParams = [
                'AppKey' => $this->appKey,
                'Target' => $appRecipient['target'],
                'TargetValue' => $appRecipient['targetValue'],
                'Title' => $message->title,
                'Body' => $message->body,
            ];
            if (!empty($message->extParameters)) {
                $messageParams['ExtParameters'] = Json::encode($message->extParameters);
            }
            $this->aliyun->getCloudPush()->pushNoticeToAndroid($messageParams);
            $messageParams['ApnsEnv'] = YII_ENV_DEV ? 'DEV' : 'PRODUCT';
            $this->aliyun->getCloudPush()->pushNoticeToIOS($messageParams);
        } else {
            print_r($message->getErrors());
            exit;
        }
    }
}