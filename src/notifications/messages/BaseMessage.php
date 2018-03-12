<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\messages;

use yii\base\BaseObject;

/**
 * Class BaseMessage
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
abstract class BaseMessage extends BaseObject
{
    /**
     * The "level" of the notification (info, success, error).
     * @var string
     */
    public $level = 'info';

    /**
     * The title of the notification.
     * @var string
     */
    public $title;

    /**
     * The notification's message body
     * @var string
     */
    public $body;
}