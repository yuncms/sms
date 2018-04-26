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
     * @param mixed $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        /**
         * @var $message MailMessage
         */
        $message = $notification->exportFor('mail');

        $this->mailer->compose()
            ->setFrom(isset($message->from) ? $message->from : $this->from)
            ->setTo($notifiable->routeNotificationFor('mail'))
            ->setSubject($message->title)
            ->send();



        $message = $notification->toMail($notifiable);

        /**
         * @var $message MailMessage
         */
        $message = $notification->exportFor('mail');

        $this->mailer->compose()
            ->setFrom(isset($message->from) ? $message->from : $this->from)
            ->setTo($recipient->routeNotificationFor('mail'))
            ->setSubject($message->title)
            ->send();

        if (! $notifiable->routeNotificationFor('mail') &&
            ! $message instanceof Mailable) {
            return;
        }

        if ($message instanceof Mailable) {
            return $message->send($this->mailer);
        }

        $this->mailer->send(
            $this->buildView($message),
            $message->data(),
            $this->messageBuilder($notifiable, $notification, $message)
        );

        /**
         * @var $message MailMessage
         */
        $message = $notification->exportFor('mail');
        $this->mailer->compose()
            ->setFrom(isset($message->from) ? $message->from : $this->from)
            ->setTo($recipient->routeNotificationFor('mail'))
            ->setSubject($message->title)
            ->send();
    }
}