<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\broadcast;

/**
 * Interface BroadcastInterface
 * @package yuncms\broadcast
 */
interface BroadcastInterface
{
    /**
     * Creates a new message instance and optionally composes its body content via view rendering.
     *
     * @param array $message the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @return MessageInterface message instance.
     */
    public function createMessage($message);

    /**
     * Sends the given broadcast message.
     * @param MessageInterface $message broadcast message instance to be sent
     * @return bool whether the message has been sent successfully
     */
    public function send($message);

    /**
     * Sends multiple messages at once.
     *
     * This method may be implemented by some broadcast which support more efficient way of sending multiple messages in the same batch.
     *
     * @param array $messages list of broadcast messages, which should be sent.
     * @return int number of messages that are successfully sent.
     */
    public function sendMultiple(array $messages);
}