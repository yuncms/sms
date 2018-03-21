<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yuncms\db\ActiveRecord;

/**
 * Sphinx RealTime Index Behavior
 * @property ActiveRecord $owner
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class SphinxRealTimeBehavior extends Behavior
{
    /**
     * @var string provide the name of realtime index from you sphinx.conf file
     */
    public $realTimeIndex;

    /**
     * @var integer the name of document ID from main document fetch query (sphinx.conf)
     */
    public $idAttributeName;

    /**
     * @var array the set of rt_field names (sphinx.conf)
     */
    public $realTimeFieldNames = [];

    /**
     * @var array the set of rt attributes
     */
    public $realTimeAttributeNames = [];

    /**
     * @var bool turning on | off the behavior
     */
    public $enabled = false;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (empty($this->realTimeIndex)) {
            throw new InvalidConfigException('The "realTimeIndex" property must be set.');
        }
        if (empty($this->idAttributeName)) {
            throw new InvalidConfigException('The "idAttributeName" property must be set.');
        }
        if (!count($this->realTimeFieldNames)) {
            throw new InvalidConfigException('The "realTimeFieldNames" property must be set.');
        }
        if (!count($this->realTimeAttributeNames)) {
            throw new InvalidConfigException('The "realTimeAttributeNames" property must be set.');
        }
    }

    /**
     * Declares event handlers for the [[owner]]'s events.
     *
     * Child classes may override this method to declare what PHP callbacks should
     * be attached to the events of the [[owner]] component.
     *
     * The callbacks will be attached to the [[owner]]'s events when the behavior is
     * attached to the owner; and they will be detached from the events when
     * the behavior is detached from the component.
     *
     * The callbacks can be any of the following:
     *
     * - method in this behavior: `'handleClick'`, equivalent to `[$this, 'handleClick']`
     * - object method: `[$object, 'handleClick']`
     * - static method: `['Page', 'handleClick']`
     * - anonymous function: `function ($event) { ... }`
     *
     * The following is an example:
     *
     * ```php
     * [
     *     Model::EVENT_BEFORE_VALIDATE => 'myBeforeValidate',
     *     Model::EVENT_AFTER_VALIDATE => 'myAfterValidate',
     * ]
     * ```
     *
     * @return array events (array keys) and the corresponding event handler methods (array values).
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
            ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete'
        ];
    }

    /**
     * @return bool
     */
    public function afterInsert()
    {
        return $this->enabled && $this->replace();
    }

    /**
     * @return bool
     */
    public function afterUpdate()
    {
        return $this->enabled && $this->replace();
    }

    /**
     * @return bool
     */
    public function afterDelete()
    {
        if (!$this->enabled) {
            return false;
        }
        $params = [];
        $sql = Yii::$app->sphinx->getQueryBuilder()->delete($this->realTimeIndex, $this->idAttributeName . '=' . $this->owner->getAttribute($this->idAttributeName), $params);
        return Yii::$app->sphinx->createCommand($sql, $params)->execute();
    }

    /**
     * @return array|mixed
     */
    protected function getColumns()
    {
        $columns = [$this->idAttributeName => $this->owner->getAttribute($this->idAttributeName)];
        $columns = $this->addColumns($columns, $this->realTimeFieldNames);
        $columns = $this->addColumns($columns, $this->realTimeAttributeNames);
        return $columns;
    }

    /**
     * @param $columns
     * @param $fieldNames
     * @return mixed
     */
    protected function addColumns($columns, $fieldNames)
    {
        foreach ($fieldNames as $name) {
            $value = $this->owner->getAttribute($name);
            if (!is_string($value)) {
                $value = strval($value);
            }
            $columns[$name] = $value;
        }
        return $columns;
    }

    /**
     * @return mixed
     */
    protected function replace()
    {
        $params = [];
        $sql = Yii::$app->sphinx->getQueryBuilder()->replace($this->realTimeIndex, $this->getColumns(), $params);
        return Yii::$app->sphinx->createCommand($sql, $params)->execute();
    }
}