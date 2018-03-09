<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\channels;

use yii\base\BaseObject;
use yuncms\notifications\contracts\ChannelInterface;
use yuncms\notifications\Notification;

abstract class Channel  extends BaseObject implements ChannelInterface
{
    /**
     * 开始推送
     * @param Notification $notification
     * @return mixed
     */
    abstract public function send(Notification $notification);
}