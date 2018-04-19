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
     * @var bool 是否确保生成的uuid值在所有者类记录中是唯一的。
     */
    public $ensureUnique = true;

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
        if (empty($this->attributes)) {
            $this->attributes = [BaseActiveRecord::EVENT_BEFORE_VALIDATE => $this->attribute];
        }
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    protected function getValue($event)
    {
        if (!empty($this->owner->{$this->attribute})) {
            return $this->owner->{$this->attribute};
        }
        if ($this->value === null) {
            $uuid = $this->generateUUID();
        } else {
            $uuid = parent::getValue($event);
        }
        return $this->ensureUnique ? $this->makeUnique($uuid) : $uuid;
    }

    /**
     * This method is called by [[getValue]] to generate the uuid.
     * You may override it to customize uuid generation.
     * @return string the result.
     * @throws \Exception
     */
    protected function generateUUID(): string
    {
        return StringHelper::UUID();
    }

    /**
     * This method is called by [[getValue]] when [[ensureUnique]] is true to generate the unique uuid.
     * @param string $uuid
     * @return string unique uuid
     * @throws InvalidConfigException
     * @throws \Exception
     * @see getValue
     */
    protected function makeUnique($uuid): string
    {
        $uniqueUUID = $uuid;
        $iteration = 0;
        while (!$this->validateUUID($uniqueUUID)) {
            $iteration++;
            $uniqueUUID = $this->generateUUID();
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