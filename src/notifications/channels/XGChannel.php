<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\channels;

use yii\base\Component;
use yuncms\notifications\contracts\ChannelInterface;
use yuncms\notifications\contracts\NotifiableInterface;
use yuncms\notifications\contracts\NotificationInterface;

/**
 * 信鸽推送
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class XGChannel extends Component implements ChannelInterface
{

    public function send(NotifiableInterface $recipient, NotificationInterface $notification)
    {
        // TODO: Implement send() method.
    }
}