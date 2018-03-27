<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\assets;


use yii\web\AssetBundle;

class UserAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@vendor/yuncms/framework/resources/assets/yuncms-user/dist';

    public $css = [
        'css/user.css'
    ];

    public $js = [
        'js/user.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}