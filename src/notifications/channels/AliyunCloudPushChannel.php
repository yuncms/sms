<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\channels;


use Yii;
use yuncms\notifications\contracts\ChannelInterface;
use yuncms\notifications\contracts\NotifiableInterface;
use yuncms\notifications\contracts\NotificationInterface;
use yuncms\notifications\messages\AppMessage;
use xutl\aliyun\jobs\PushNoticeToMobile;

/**
 * App 推送渠道
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class AliyunCloudPushChannel implements ChannelInterface
{
    /**
     * @var string
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
        $this->mailer = Instance::ensure($this->mailer, 'yii\mail\MailerInterface');
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
        $cloudPush = Yii::$app->aliyun->getCloudPush();
        $cloudPush->pushNoticeToAndroid([
            'AppKey' => $this->_appKey,
            'Target' => $this->target,
            'TargetValue' => $this->targetValue,
            'Title' => $this->title,
            'Body' => $this->body,
            'ExtParameters' => $this->extParameters,//JSON
        ]);
        Yii::$app->queue->push(new PushNoticeToMobile([
            'target' => $appRecipient->target,
            'targetValue' => $appRecipient->targetValue,
            'title' => $message->title,
            'body' => $message->body,
            'extParameters' => $message->extParameters
        ]));
    }
}