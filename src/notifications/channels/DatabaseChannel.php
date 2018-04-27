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
use yuncms\notifications\models\DatabaseNotification;
use yuncms\notifications\Notification;

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
     * @param  Notification $notification
     * @return mixed
     */
    public function send(NotifiableInterface $notifiable, Notification $notification)
    {
        /** @var ActiveRecord $modelClass */
        $notifiable = $notifiable->routeNotificationFor('database');

        return DatabaseNotification::create([
            'id' => $notification->id,
            'verb' => $notification->verb,
            'template' => $notification->getTemplate(),
            'notifiable_id' => $notifiable['notifiable_id'],
            'notifiable_class' => $notifiable['notifiable_class'],
            'data' => $notification->getData(),
        ]);
    }
}