<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications;


trait Notifiable
{
    use HasDatabaseNotifications, RoutesNotifications;

    /**
     * 确定通知实体是否应通过签入通知设置来接收通知。
     * @param NotificationInterface $notification
     * @return bool
     */
    public function shouldReceiveNotification(NotificationInterface $notification)
    {
        $alias = get_class($notification);
        if (isset($this->notificationSettings)) {
            $settings = $this->notificationSettings;
            if (array_key_exists($alias, $settings)) {
                if ($settings[$alias] instanceof \Closure) {
                    return call_user_func($settings[$alias], $notification);
                }
                return (bool)$settings[$alias];
            }
        }
        return true;
    }
}