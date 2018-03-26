<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\widgets;

use yii\web\AssetBundle;

/**
 * TypeAheadPluginAsset
 */
class BootstrapTypeAheadPluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/yuncms/framework/resources/lib/typeahead.js';

    public $js = [
        'typeahead.bundle.min.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}