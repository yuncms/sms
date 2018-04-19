<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\validators;

use yii\base\InvalidArgumentException;
use yii\db\BaseActiveRecord;
use yii\validators\Validator;
use yuncms\base\JsonObject;

/**
 * Class JsonValidator
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class JsonValidator extends Validator
{
    /**
     * @var bool
     */
    public $merge = false;

    /**
     * Map json error constant to message
     * @see: http://php.net/manual/ru/json.constants.php
     * @var array
     */
    public $errorMessages = [];

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
        if (!$value instanceof JsonObject) {
            try {
                $new = new JsonObject($value);
                if ($this->merge) {
                    /** @var BaseActiveRecord $model */
                    $old = new JsonObject($model->getOldAttribute($attribute));
                    $new = new JsonObject(array_merge($old->toArray(), $new->toArray()));
                }
                $model->$attribute = $new;
            } catch (InvalidArgumentException $e) {
                $this->addError($model, $attribute, $this->getErrorMessage($e));
                $model->$attribute = new JsonObject();
            }
        }
    }

    /**
     * @param \Exception $exception
     * @return string
     */
    protected function getErrorMessage($exception)
    {
        $code = $exception->getCode();
        if (isset($this->errorMessages[$code])) {
            return $this->errorMessages[$code];
        }
        return $exception->getMessage();
    }
}