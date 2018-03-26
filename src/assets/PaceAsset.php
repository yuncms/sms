<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\assets;

use yii\web\AssetBundle;

/**
 * Class SwitcheryAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class PaceAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@vendor/yuncms/framework/resources/lib/pace';

    /**
     * @inherit
     */
    public $js = [
        'pace.min.js'
    ];
}