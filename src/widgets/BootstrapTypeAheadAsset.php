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
class BootstrapTypeAheadAsset extends AssetBundle
{
    public $sourcePath = '@vendor/yuncms/framework/resources/lib/typeahead.js';

    public $css = [
        'bootstrap-typeahead/css/bootstrap-typeahead.css',
    ];

    public $js = [
        'bootstrap-typeahead/js/typeahead.bundle.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}