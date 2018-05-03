<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\widgets;

use yii\base\Model;
use yuncms\widgets\ActiveField;

/**
 * Class ActiveForm
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ActiveForm extends \yii\bootstrap\ActiveForm
{
    /**
     * @var string the default field class name when calling [[field()]] to create a new field.
     * @see fieldConfig
     */
    public $fieldClass = ActiveField::class;

    /**
     * @param Model $model the data model.
     * @param string $attribute the attribute name or expression. See [[Html::getAttributeName()]] for the format
     * about attribute expression.
     * @param array $options the additional configurations for the field object. These are properties of [[ActiveField]]
     * or a subclass, depending on the value of [[fieldClass]].
     * @return \yii\bootstrap\ActiveField|ActiveField
     */
    public function field($model, $attribute, $options = [])
    {
        if ($this->layout == 'inline' && !isset($options['inputOptions']['placeholder'])) {
            $options['inputOptions']['placeholder'] = $model->getAttributeLabel($attribute);
        }
        return parent::field($model, $attribute, $options);
    }
}