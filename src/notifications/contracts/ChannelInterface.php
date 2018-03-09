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
     * 开始推送
     * @param Notification $notification
     * @return mixed
     */
    public function send(Notification $notification);
}