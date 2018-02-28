<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\behaviors;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;
use yii\validators\UniqueValidator;
use yuncms\helpers\StringHelper;

/**
 * UUIDBehavior automatically fills the specified attribute with a value that can be used a uuid in a URL.
 *
 * To use UUIDBehavior, insert the following code to your ActiveRecord class:
 *
 * ```php
 * use yuncms\behaviors\UUIDBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => UUIDBehavior::class,
 *             'attribute' => 'uuid',
 *         ],
 *     ];
 * }
 * ```
 *
 * By default, UUIDBehavior will fill the `uuid` attribute with a value that can be used a uuid in a URL
 * when the associated AR object is being validated.
 *
 * Because attribute values will be set automatically by this behavior, they are usually not user input and should therefore
 * not be validated, i.e. the `uuid` attribute should not appear in the [[\yii\base\Model::rules()|rules()]] method of the model.
 *
 * If your attribute name is different, you may configure the [[uuidAttribute]] property like the following:
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => UUIDBehavior::class,
 *             'uuidAttribute' => 'alias',
 *         ],
 *     ];
 * }
 * ```
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UUIDBehavior extends AttributeBehavior
{
    /**
     * @var string the attribute that will receive the uuid value
     */
    public $attribute = 'uuid';

    /**
     * @var bool whether to generate a new uuid if it has already been generated before.
     * If true, the behavior will not generate a new slug even if [[attribute]] is changed.
     */
    public $immutable = false;

    /**
     * @var bool whether to ensure generated uuid value to be unique among owner class records.
     * If enabled behavior will validate slug uniqueness automatically. If validation fails it will attempt
     * generating unique uuid value from based one until success.
     */
    public $ensureUnique = false;

    /**
     * @var array configuration for uuid uniqueness validator. Parameter 'class' may be omitted - by default
     * [[UniqueValidator]] will be used.
     * @see UniqueValidator
     */
    public $uniqueValidator = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->attribute === null) {
            throw new InvalidConfigException('Either "attribute" property must be specified.');
        }
        if (empty($this->attributes)) {
            $this->attributes = [BaseActiveRecord::EVENT_BEFORE_VALIDATE => $this->attribute];
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getValue($event)
    {
        if (!$this->isNewUUIDNeeded()) {
            return $this->owner->{$this->attribute};
        }
        $uuid = parent::getValue($event);
        return $this->ensureUnique ? $this->makeUnique($uuid) : $uuid;
    }

    /**
     * Checks whether the new uuid generation is needed
     * This method is called by [[getValue]] to check whether the new uuid generation is needed.
     * You may override it to customize checking.
     * @return bool
     */
    protected function isNewUUIDNeeded()
    {
        if (empty($this->owner->{$this->attribute})) {
            return true;
        }

        if ($this->immutable) {
            return false;
        }

        return false;
    }

    /**
     * This method is called by [[getValue]] when [[ensureUnique]] is true to generate the unique uuid.
     * @param string $uuid
     * @return string unique uuid
     * @throws InvalidConfigException
     * @throws \Exception
     * @see getValue
     */
    protected function makeUnique($uuid)
    {
        $uniqueUUID = $uuid;
        $iteration = 0;
        while (!$this->validateUUID($uniqueUUID)) {
            $iteration++;
            $uniqueUUID = StringHelper::UUID();
        }
        return $uniqueUUID;
    }

    /**
     * Checks if given uuid value is unique.
     * @param string $uuid slug value
     * @return bool whether slug is unique.
     * @throws InvalidConfigException
     */
    protected function validateUUID($uuid)
    {
        /* @var $validator UniqueValidator */
        /* @var $model BaseActiveRecord */
        $validator = Yii::createObject(array_merge([
            'class' => UniqueValidator::class,
        ], $this->uniqueValidator));

        $model = clone $this->owner;
        $model->clearErrors();
        $model->{$this->attribute} = $uuid;

        $validator->validateAttribute($model, $this->attribute);
        return !$model->hasErrors();
    }
}