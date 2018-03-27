<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\assets;

use yii\web\AssetBundle;

/**
 * Class CropperAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class CropperAsset extends AssetBundle
{
    public $sourcePath = '@vendor/yuncms/framework/resources/lib/cropper';

    public $css = [
        'cropper.min.css',
    ];

    public $js = [
        'cropper.min.js',
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}