<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications;

use Yii;
use yii\helpers\Inflector;
use yuncms\notifications\models\DatabaseNotification;

/**
 * Trait RoutesNotificationsTrait
 * @package yuncms\notifications
 */
trait RoutesNotifications
{
    /**
     * 发送给定的通知。
     *
     * @param  mixed $instance
     * @return void
     */
    public function notify($instance)
    {
        Yii::$app->notification->send($this, $instance);
    }

    /**
     * 立即发送给定的通知。
     *
     * @param  mixed $instance
     * @param  array|null $channels
     * @return void
     */
    public function notifyNow($instance, array $channels = null)
    {
        Yii::$app->notification->sendNow($this, $instance, $channels);
    }

    /**
     * 获取给定驱动程序的通知路由信息。
     *
     * @param  string $driver
     * @return mixed
     */
    public function routeNotificationFor($driver)
    {
        if (method_exists($this, $method = 'routeNotificationFor' . Inflector::camelize($driver))) {
            return $this->{$method}();
        }

        switch ($driver) {
            case 'database':
                return DatabaseNotification::class;
            case 'mail':
                return $this->email;
            case 'sms':
                return $this->mobile;
        }
        return false;
    }
}