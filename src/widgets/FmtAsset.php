<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\widgets;


use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@vendor/yuncms/framework/resources/css/fmt';

    /**
     * @inherit
     */
    public $css = [
        'fmt.css',
    ];
}