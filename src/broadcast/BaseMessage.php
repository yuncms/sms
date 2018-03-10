<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\broadcast;


use Yii;
use yii\base\BaseObject;
use yii\base\ErrorHandler;
use yuncms\helpers\Json;

/**
 * Class BaseMessage
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BaseMessage extends BaseObject implements MessageInterface
{
    /**
     * @var BroadcastInterface the broadcast instance that created this message.
     * For independently created messages this is `null`.
     */
    public $broadcast;

    /**
     * @var array
     */
    protected $body;

    /**
     * @var string|array message tag.
     */
    protected $tag;

    /**
     * @var null|array message attributes.
     */
    protected $attributes;

    /**
     * set the message body
     * @param array $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * get the message body
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * get the message tag
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * set the message tag
     * @param string $tag
     * @return BaseMessage
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * set message attributes
     * @param array $attributes
     * @return BaseMessage
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Sends this broadcast message.
     * @param BroadcastInterface $broadcast the broadcast that should be used to send this message.
     * If no broadcast is given it will first check if [[broadcast]] is set and if not,
     * the "broadcast" application component will be used instead.
     * @return bool whether this message is sent successfully.
     */
    public function send(BroadcastInterface $broadcast = null)
    {
        if ($broadcast === null && $this->broadcast === null) {
            $broadcast = Yii::$app->getBroadcast();
        } elseif ($broadcast === null) {
            $broadcast = $this->broadcast;
        }

        return $broadcast->send($this);
    }

    /**
     * Returns string representation of this message.
     * @return string the string representation of this message.
     */
    public function toString()
    {
        return Json::encode($this->body);
    }

    /**
     * PHP magic method that returns the string representation of this object.
     * @return string the string representation of this object.
     */
    public function __toString()
    {
        // __toString cannot throw exception
        // use trigger_error to bypass this limitation
        try {
            return $this->toString();
        } catch (\Exception $e) {
            ErrorHandler::convertExceptionToError($e);
            return '';
        }
    }
}