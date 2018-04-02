<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\assets;

use yii\web\AssetBundle;

/**
 * Class BlueimpFileUploadAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BlueimpFileUploadAsset extends AssetBundle
{
    public $sourcePath = '@vendor/yuncms/framework/resources/lib/blueimp-file-upload';

    public $css = [
        'css/jquery.fileupload.css'
    ];

    public $js = [
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js',
        'js/jquery.fileupload-process.js',
        'js/jquery.fileupload-image.js',
        'js/jquery.fileupload-validate.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yuncms\assets\BlueimpLoadImageAsset'
    ];
}