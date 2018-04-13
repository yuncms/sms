<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace framework\src\notifications\channels;

use yii\base\Component;
use yuncms\notifications\contracts\ChannelInterface;
use yuncms\notifications\contracts\NotificationInterface;
use yuncms\notifications\contracts\RecipientInterface;
use yuncms\notifications\messages\WebMessage;
use yuncms\notifications\models\Notification;

/**
 * Class WebChannel
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class WebChannel extends Component implements ChannelInterface
{
    /**
     * @param RecipientInterface $recipient
     * @param NotificationInterface $notification
     */
    public function send(RecipientInterface $recipient, NotificationInterface $notification)
    {
        /**
         * @var $message WebMessage
         */
        $message = $notification->exportFor('web');
        Notification::createAsync($message->toArray());
    }
}