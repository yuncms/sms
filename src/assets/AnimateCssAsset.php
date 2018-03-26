<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\assets;

use yii\web\AssetBundle;

/**
 * Class AnimateCssAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class AnimateCssAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@vendor/yuncms/framework/resources/lib/animate.css';

    /**
     * @inherit
     */
    public $css = [
        'animate.min.css',
    ];
}