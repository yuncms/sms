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
    public $sourcePath = '@vendor/yuncms/framework/resources/assets/bootstrap-typeahead';

    public $css = [
        'bootstrap-typeahead.css',
    ];

    public $depends = [
        'yuncms\widgets\BootstrapTypeAheadPluginAsset',
    ];
}