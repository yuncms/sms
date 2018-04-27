<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications;

use Yii;
use yii\base\Arrayable;
use yii\base\BaseObject;
use yuncms\helpers\ArrayHelper;
use yuncms\helpers\StringHelper;

abstract class Notification extends BaseObject implements Arrayable
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
    public $verb;

    /**
     * @var array 通知数据
     */
    public $data = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!$this->id) {
            $this->id = StringHelper::ObjectId();
        }
    }

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
     * Gets the notification title
     *
     * @return string
     */
    abstract public function getTemplate();

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
     * 返回写数据库的数组
     * @return array
     */
    public function toDatabase()
    {
        return ArrayHelper::toArray($this);
    }

    /**
     * Sends this notification to all channels
     */
    public function send()
    {
        Yii::$app->notification->send($this);
    }
}