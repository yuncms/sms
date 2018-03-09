<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications;

use yii\base\BaseObject;
use yuncms\notifications\channels\Channel;
use yuncms\notifications\contracts\NotificationInterface;

/**
 * Class Notification
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Notification extends BaseObject implements NotificationInterface
{
    /**
     * Determines if the notification can be sent.
     *
     * @param  Channel $channel
     * @return bool
     */
    public function shouldSend($channel): bool
    {
        return true;
    }
}