<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin\widgets;

use yii\web\AssetBundle;

class SwitcheryAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@vendor/xutl/yii2-inspinia-widget/assets';

    public $css = [
        'css/plugins/switchery/switchery.css',
    ];

    /**
     * @inherit
     */
    public $js = [
        'js/plugins/switchery/switchery.js',
    ];

    public $depends = [
        'xutl\inspinia\InspiniaAsset',
    ];
}