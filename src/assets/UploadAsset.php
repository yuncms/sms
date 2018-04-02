<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\assets;

use yii\web\AssetBundle;

/**
 * Class UploadAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UploadAsset extends AssetBundle
{
    public $sourcePath = '@vendor/yuncms/framework/resources/assets/yuncms-upload';

    public $css = [
        'css/upload.css'
    ];

    public $js = [
        'js/upload.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yuncms\assets\BlueimpFileUploadAsset'
    ];
}