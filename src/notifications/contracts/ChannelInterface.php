<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\contracts;

use yuncms\notifications\Notifiable;

/**
 * Interface ChannelInterface
 * @package yuncms\notifications\contracts
 */
interface ChannelInterface
{
    /**
     * Get channel name.
     *
     * @return string
     */
    public function getName();

    /**
     * 开始推送
     * @param NotificationInterface $notification
     * @return mixed
     */
    public function send(NotificationInterface $notification);
}