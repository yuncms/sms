<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\base;

use yuncms\notifications\NotificationManager;
use yuncms\payment\PaymentManager;

/**
 * Trait ApplicationTrait
 * @property \yuncms\components\Settings $settings The settings manager component
 * @property \yii\authclient\Collection $authClientCollection The authClient Collection component
 * @property \yii\queue\Queue $queue The queue component
 * @property \yuncms\notifications\NotificationManager $notifications The notifications component
 * @property \yii\redis\Connection $redis The redis component
 * @property \yuncms\filesystem\FilesystemManager $filesystem The filesystem component
 * @property \yuncms\broadcast\BaseBroadcast $broadcast The broadcast component
 * @property \yuncms\notifications\NotificationManager $notification the notification connection.
 * @property \yuncms\payment\PaymentManager $payment the payment connection.
 * @property \yuncms\mq\BaseMessageQueue $messageQueue the message queue connection.
 */
trait ApplicationTrait
{
    /**
     * @return \yuncms\payment\PaymentManager
     */
    public function getPayment(): PaymentManager
    {
        return $this->get('payment');
    }

    /**
     * Returns the message queue component.
     * @return \yuncms\mq\BaseMessageQueue the broadcast connection.
     */
    public function getMessageQueue()
    {
        return $this->get('messageQueue');
    }

    /**
     * Returns the broadcast component.
     * @return \yuncms\broadcast\BaseBroadcast the broadcast connection.
     */
    public function getBroadcast()
    {
        return $this->get('broadcast');
    }

    /**
     * Returns the queue component.
     * @return \yii\queue\Queue the queue connection.
     */
    public function getQueue()
    {
        return $this->get('queue');
    }

    /**
     * Return The redis component
     * @return \yii\redis\Connection
     */
    public function getRedis()
    {
        return $this->get('redis');
    }

    /**
     * Returns the notifications component.
     * @return \yuncms\notifications\NotificationManager the notifications connection.
     */
    public function getNotifications(): NotificationManager
    {
        return $this->get('notification');
    }

    /**
     * Returns the authClientCollection component.
     * @return \yii\authclient\Collection the authClientCollection connection.
     */
    public function getAuthClientCollection(): \yii\authclient\Collection
    {
        return $this->get('authClientCollection');
    }

    /**
     * Returns the settings component.
     * @return \yuncms\filesystem\FilesystemManager the filesystem connection.
     */
    public function getFilesystem(): \yuncms\filesystem\FilesystemManager
    {
        return $this->get('filesystem');
    }

    /**
     * Returns the settings component.
     * @return \yuncms\components\Settings the settings connection.
     */
    public function getSettings(): \yuncms\components\Settings
    {
        return $this->get('settings');
    }
}