<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\channels;

use yii\base\Component;
use yii\di\Instance;
use yii\mail\MailerInterface;
use yuncms\notifications\contracts\ChannelInterface;
use yuncms\notifications\contracts\NotifiableInterface;
use yuncms\notifications\messages\MailMessage;
use yuncms\notifications\Notification;

/**
 * Class MailChannel
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class MailChannel extends Component implements ChannelInterface
{
    /**
     * @var $mailer MailerInterface|array|string the mailer object or the application component ID of the mailer object.
     */
    public $mailer = 'mailer';

    /**
     * The message sender.
     * @var string
     */
    public $from;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $this->mailer = Instance::ensure($this->mailer, 'yii\mail\MailerInterface');
    }

    /**
     * @param NotifiableInterface $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send(NotifiableInterface $notifiable, Notification $notification)
    {
        /**
         * @var $message MailMessage
         */
        $message = $notification->toMail();
        $this->mailer->compose()
            ->setFrom(isset($message->from) ? $message->from : $this->from)
            ->setTo($notifiable->routeNotificationFor('mail'))
            ->setSubject($message->title)
            ->send();
    }
}