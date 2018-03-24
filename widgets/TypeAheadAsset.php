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
class TypeAheadAsset extends AssetBundle
{
    public $sourcePath = '@yuncms/admin/resources/assets';

    public $css = [
        'bootstrap-typeahead/css/bootstrap-typeahead.css',
    ];

    public $js = [
        'bootstrap-typeahead/js/typeahead.bundle.min.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}