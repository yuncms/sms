<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications;

use Yii;
use yii\base\BaseObject;
use yuncms\helpers\ArrayHelper;
use yuncms\helpers\StringHelper;

/**
 * Class Notification
 * @method toMail
 * @method toCloudPush
 * @method toSms
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
abstract class Notification extends BaseObject
{
    /** @var string */
    public $id;

    /**
     * @var string
     */
    public $verb;

    /** @var array */
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
     * 获取通知标题
     * @return null
     */
    public function getTitle()
    {
        return null;
    }

    /**
     * 获取消息模板
     * @return string
     */
    abstract public function getTemplate();

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
     * 获取详细内容
     * @return string
     */
    public function getContent()
    {
        $params = $this->getData();
        $p = [];
        foreach ($params as $name => $value) {
            $p['{' . $name . '}'] = $value;
        }
        return strtr($this->getTemplate(), $p);
    }

    /**
     * 确定通知将传送到哪个频道
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }

    /**
     * 获取消息数组
     * @param array $expand
     * @param bool $recursive
     * @return array
     */
    public function toArray(array $expand = [], $recursive = true)
    {
        return ArrayHelper::toArray($this, $expand, $recursive);
    }
}