<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\channels;

use RuntimeException;
use yuncms\db\ActiveRecord;
use yuncms\notifications\contracts\NotifiableInterface;
use yuncms\notifications\contracts\NotificationInterface;
use yuncms\notifications\models\DatabaseNotification;

/**
 * Class DatabaseChannel
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class DatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param  NotifiableInterface $notifiable
     * @param  NotificationInterface $notification
     * @return mixed
     */
    public function send(NotifiableInterface $notifiable, NotificationInterface $notification)
    {
        /** @var ActiveRecord $modelClass */
        $notifiable = $notifiable->routeNotificationFor('database');

        print_r($notifiable);exit;

        return DatabaseNotification::create([
            'id' => $notification->id,
            'verb' => get_class($notification),
            'notifiable_id' => $notifiable['notifiable_id'],
            'notifiable_class' => $notifiable['notifiable_class'],
            'data' => $this->getData($notifiable, $notification),
            'read_at' => null,
        ]);
    }

    /**
     * Get the data for the notification.
     *
     * @param  NotifiableInterface $notifiable
     * @param  NotificationInterface $notification
     * @return array
     *
     * @throws \RuntimeException
     */
    protected function getData(NotifiableInterface $notifiable, NotificationInterface $notification)
    {
        if (method_exists($notification, 'toDatabase')) {
            return $notification->toDatabase($notifiable);
        }

        if (method_exists($notification, 'toArray')) {
            return $notification->toArray($notifiable);
        }

        throw new RuntimeException(
            'Notification is missing toDatabase / toArray method.'
        );
    }
}