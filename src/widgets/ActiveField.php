<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\widgets;

use Yii;
use yuncms\helpers\ArrayHelper;

/**
 * Class ActiveField
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ActiveField extends \yii\bootstrap\ActiveField
{
    /**
     * 显示文件上传窗口
     * @param array $options
     * @return \yii\bootstrap\ActiveField
     */
    public function fileInput($options = [])
    {
        $options = ArrayHelper::merge([
            'class' => 'filestyle',
            'data' => [
                'buttonText' => Yii::t('yuncms', 'Choose file'),
                //'size' => 'lg'
            ]
        ], $options);
        return parent::fileInput($options);
    }

    /**
     * 显示下拉
     * @param array $items
     * @param array $options
     * @param bool $generateDefault
     * @return \yii\bootstrap\ActiveField
     */
    public function dropDownList($items, $options = [], $generateDefault = true)
    {
        if ($generateDefault === true && !isset($options['prompt'])) {
            $options['prompt'] = yii::t('yuncms', 'Please select');
        }
        return parent::dropDownList($items, $options);
    }

    /**
     * @param array $options
     * @return \yii\bootstrap\ActiveField
     */
    public function textarea($options = [])
    {
        if (!isset($options['rows'])) {
            $options['rows'] = 5;
        }
        return parent::textarea($options);
    }
}