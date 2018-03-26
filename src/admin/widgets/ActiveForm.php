<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin\widgets;

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
    public $fieldClass = 'yuncms\admin\widgets\ActiveField';

    /**
     * @inheritdoc
     * @return \yii\bootstrap\ActiveField|ActiveField
     */
    public function field($model, $attribute, $options = [])
    {
        return parent::field($model, $attribute, $options);
    }
}