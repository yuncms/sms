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

}