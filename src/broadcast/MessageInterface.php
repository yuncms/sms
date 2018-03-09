<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\broadcast;

/**
 * MessageInterface is the interface that should be implemented by broadcast message classes.
 *
 * A message represents the settings and content of an broadcast, such as the sender, recipient,
 * subject, body, etc.
 *
 * Messages are sent by a [[\yuncms\broadcast\BroadcastInterface|broadcast]], like the following,
 *
 * ```php
 * Yii::$app->broadcast->compose(['aaa'=>'bbb'])
 *     ->send();
 * ```
 *
 * @see BroadcastInterface
 * @package yuncms\broadcast
 */
interface MessageInterface
{
    /**
     * Sends this email message.
     * @param BroadcastInterface $broadcast the broadcast that should be used to send this message.
     * If null, the "broadcast" application component will be used instead.
     * @return bool whether this message is sent successfully.
     */
    public function send(BroadcastInterface $broadcast = null);

    /**
     * Returns string representation of this message.
     * @return string the string representation of this message.
     */
    public function toString();
}