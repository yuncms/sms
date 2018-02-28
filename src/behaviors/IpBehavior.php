<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yuncms\web\Application;

/**
 * Class IpBehavior
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class IpBehavior extends AttributeBehavior
{
    /**
     * @var string the attribute that will receive IP value on create.
     * Set to false if you do not want record it
     */
    public $createdIpAttribute = 'created_ip';

    /**
     * @var string the attribute that will receive IP value on update.
     * Set to false if you do not want record it
     */
    public $updatedIpAttribute = 'updated_ip';

    /**
     * @var callable|string
     * This can be either an anonymous function that returns the IP value or a string.
     * If not set, it will use the value of `Yii::$app->request->userIP` to set the attributes.
     * NOTE! Null is returned if the user IP address cannot be detected.
     */
    public $value;

    /**
     * @var mixed 无法获取正确的用户IP时的默认值
     */
    public $defaultValue;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->createdIpAttribute, $this->updatedIpAttribute],
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->updatedIpAttribute,
            ];
        }
    }

    /**
     * {@inheritdoc}
     *
     * In case, when the [[value]] property is `null`, the value of [[defaultValue]] will be used as the value.
     */
    protected function getValue($event)
    {
        if ($this->value === null && Yii::$app instanceof Application) {
            $ip = Yii::$app->request->userIP;
            if ($ip === null) {
                return $this->getDefaultValue($event);
            }
            return $ip;
        }
        return parent::getValue($event);
    }

    /**
     * Get default value
     * @param \yii\base\Event $event
     * @return array|mixed
     */
    protected function getDefaultValue($event)
    {
        if ($this->defaultValue instanceof \Closure || (is_array($this->defaultValue) && is_callable($this->defaultValue))) {
            return call_user_func($this->defaultValue, $event);
        }
        return $this->defaultValue;
    }
}