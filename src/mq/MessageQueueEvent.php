<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\mq;

use yii\base\Event;

/**
 * BroadcastEvent represents the event parameter used for events triggered by [[BaseBroadcast]].
 *
 * By setting the [[isValid]] property, one may control whether to continue running the action.
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class MessageQueueEvent extends Event
{
    /**
     * @var \yuncms\mq\MessageQueueInterface the broadcast message being send.
     */
    public $message;

    /**
     * @var bool if message was sent successfully.
     */
    public $isSuccessful;

    /**
     * @var bool whether to continue sending an broadcast. Event handlers of
     * [[\yuncms\mq\BaseBroadcast::EVENT_BEFORE_SEND]] may set this property to decide whether
     * to continue send or not.
     */
    public $isValid = true;
}