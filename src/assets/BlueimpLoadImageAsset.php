<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\assets;


use yii\web\AssetBundle;

/**
 * Class BlueimpLoadImageAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BlueimpLoadImageAsset extends AssetBundle
{
    public $sourcePath = '@vendor/yuncms/framework/resources/lib/blueimp-load-image';

    public $js = [
        'load-image.all.min.js'
    ];
}