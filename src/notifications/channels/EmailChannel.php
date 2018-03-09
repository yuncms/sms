<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\channels;


use yii\di\Instance;
use yuncms\notifications\contracts\NotificationInterface;

class EmailChannel extends Channel
{
    /**
     * @var \yii\mail\MailerInterface|array|string the mailer object or the application component ID of the mailer object.
     * After the EmailChannel object is created, if you want to change this property, you should only assign it
     * with a mailer object.
     */
    public $mailer = 'mailer';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->mailer = Instance::ensure($this->mailer, 'yii\mail\MailerInterface');
    }

    /**
     * Get channel name.
     * @return string
     */
    public function getName()
    {
        return 'EMail';
    }

    /**
     * Sends a notification in this channel.
     * @param NotificationInterface $notification
     */
    public function send(NotificationInterface $notification)
    {

    }
}