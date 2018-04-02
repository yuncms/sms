<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\assets;

use yii\web\AssetBundle;

/**
 * Class AvatarAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class AvatarAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@vendor/yuncms/framework/resources/assets/yuncms-user';

    public $css = [
        'css/cropper.css',
    ];

    public $js = [
        'js/cropper.js',
    ];

    public $depends = [
        'yuncms\assets\CropperAsset',
    ];
}