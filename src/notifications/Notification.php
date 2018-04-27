<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications;


use yii\base\BaseObject;
use yuncms\helpers\StringHelper;
use yuncms\notifications\contracts\NotificationInterface;

/**
 * Class Notification
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
abstract class Notification extends BaseObject implements NotificationInterface
{
    use NotificationTrait;

    /** @var string */
    public $id;

    /**
     * @var string
     */
    public $verb;

    /** @var array  */
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
}