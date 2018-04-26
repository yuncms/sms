<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\contracts;

use yuncms\notifications\Notification;

/**
 * Interface ChannelInterface
 * @package yuncms\notifications\contracts
 */
interface ChannelInterface
{
    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  Notification $notification
     * @return mixed
     */
    public function send($notifiable, Notification $notification);
}