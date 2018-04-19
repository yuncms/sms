<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\behaviors;

use yii\base\Behavior;
use yuncms\base\JsonObject;
use yuncms\db\ActiveRecord;

/**
 * Class JsonBehavior
 *
 * @property ActiveRecord $owner
 * @author Tongle Xu <xutongle@gmail.com>
 */
class JsonBehavior extends Behavior
{
    /**
     * @var array
     */
    public $attributes = [];

    /**
     * @var null|string
     */
    public $emptyValue;

    /**
     * @var bool
     */
    public $encodeBeforeValidation = true;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_INIT => function () {
                $this->initialization();
            },
            ActiveRecord::EVENT_AFTER_FIND => function () {
                $this->decode();
            },
            ActiveRecord::EVENT_BEFORE_INSERT => function () {
                $this->encode();
            },
            ActiveRecord::EVENT_BEFORE_UPDATE => function () {
                $this->encode();
            },
            ActiveRecord::EVENT_AFTER_INSERT => function () {
                $this->decode();
            },
            ActiveRecord::EVENT_AFTER_UPDATE => function () {
                $this->decode();
            },
            ActiveRecord::EVENT_BEFORE_VALIDATE => function () {
                if ($this->encodeBeforeValidation) {
                    $this->encodeValidate();
                }
            },
            ActiveRecord::EVENT_AFTER_VALIDATE => function () {
                if ($this->encodeBeforeValidation) {
                    $this->decode();
                }
            },
        ];
    }

    /**
     * 初始化
     */
    protected function initialization()
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->setAttribute($attribute, new JsonObject());
        }
    }

    /**
     */
    protected function decode()
    {
        foreach ($this->attributes as $attribute) {
            $value = $this->owner->getAttribute($attribute);
            if (!$value instanceof JsonObject) {
                $value = new JsonObject($value);
            }
            $this->owner->setAttribute($attribute, $value);
        }
    }

    /**
     */
    protected function encode()
    {
        foreach ($this->attributes as $attribute) {
            $field = $this->owner->getAttribute($attribute);
            if (!$field instanceof JsonObject) {
                $field = new JsonObject($field);
            }
            $this->owner->setAttribute($attribute, (string)$field ?: $this->emptyValue);
        }
    }

    /**
     */
    protected function encodeValidate()
    {
        foreach ($this->attributes as $attribute) {
            $field = $this->owner->getAttribute($attribute);
            if ($field instanceof JsonObject) {
                $this->owner->setAttribute($attribute, (string)$field ?: null);
            }
        }
    }
}