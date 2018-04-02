<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\assets;

use yii\web\AssetBundle;

/**
 * Class BlueimpTmplAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BlueimpTmplAsset extends AssetBundle
{
    public $sourcePath = '@vendor/yuncms/framework/resources/lib/blueimp-tmpl';

    public $js = [
        'tmpl.min.js'
    ];
}