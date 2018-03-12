<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\messages;

/**
 * Class SmsMessage
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class SmsMessage extends BaseMessage
{
    /**
     * A phone number in E.164 format.
     * @var string
     */
    public $from;

    /**
     * The text of the message you want to send, limited to 1600 characters.
     * @var string
     */
    public $body;

    /**
     * The URL of the media you wish to send out with the message.
     * @var string
     */
    public $mediaUrl;
}