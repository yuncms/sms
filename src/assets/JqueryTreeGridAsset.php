<?php
namespace yuncms\assets;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the [jQuery TreeGrid plugin library](https://github.com/maxazan/jquery-treegrid)
 *
 * @author Leandro Gehlen <leandrogehlen@gmail.com>
 */
class JqueryTreeGridAsset extends AssetBundle {

    public $sourcePath = '@vendor/yuncms/framework/resources/lib/jquery-treegrid';

    public $js = [
        'js/jquery.treegrid.js',
    ];

    public $css = [
        'css/jquery.treegrid.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

} 