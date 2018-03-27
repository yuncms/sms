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
    public $sourcePath = '@yuncms/user/frontend/views/assets';

    public $css = [
        'css/cropper.css',
        'css/user.css'
    ];

    public $js = [
        'js/cropper.js',
        'js/user.js',
    ];
}