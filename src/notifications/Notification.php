<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications;

use Yii;
use yii\base\BaseObject;
use yii\helpers\Inflector;

class Notification extends BaseObject
{
    /**
     * The unique identifier for the notification.
     *
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $action;

    /**
     * @var array 通知数据
     */
    public $data = [];

    /**
     * Create an instance
     *
     * @param string $verb
     * @param array $params notification properties
     * @return static the newly created Notification
     * @throws \Exception
     */
    public static function create($verb, $params = [])
    {
        $params['verb'] = $verb;
        return new static($params);
    }

    /**
     * Determines if the notification can be sent.
     *
     * @param  Channel $channel
     * @return bool
     */
    public function shouldSend($channel)
    {
        return true;
    }

    /**
     * Gets the notification title
     *
     * @return string
     */
    abstract public function getTitle();

    /**
     * Gets the notification description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return null;
    }

    /**
     * Gets notification data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Sets notification data
     *
     * @param array $data
     * @return $this
     */
    public function setData($data = [])
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Sends this notification to all channels
     * @throws \yii\base\InvalidConfigException
     */
    public function send()
    {
        Yii::$app->notification->send($this);
    }
}