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
        $modelClass = $notifiable->routeNotificationFor('database');
        return $modelClass::create([
            'id' => $notification->id,
            'verb' => get_class($notification),
            'data' => $this->getData($notifiable, $notification),
            'read_at' => null,
        ]);
    }

    /**
     * Get the data for the notification.
     *
     * @param  mixed $notifiable
     * @param  Notification $notification
     * @return array
     *
     * @throws \RuntimeException
     */
    protected function getData($notifiable, NotificationInterface $notification)
    {
        if (method_exists($notification, 'toDatabase')) {
            return is_array($data = $notification->toDatabase($notifiable))
                ? $data : $data->data;
        }

        if (method_exists($notification, 'toArray')) {
            return $notification->toArray($notifiable);
        }

        throw new RuntimeException(
            'Notification is missing toDatabase / toArray method.'
        );
    }
}