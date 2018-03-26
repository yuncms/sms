<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web\assets\switchery;

use yii\web\AssetBundle;

/**
 * Class SwitcheryAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class SwitcheryAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = __DIR__ . '/dist';

    public $css = [
        'switchery.css',
    ];

    /**
     * @inherit
     */
    public $js = [
        'switchery.min.js'
    ];

    public $depends = [
        'yuncms\web\assets\switchery\SwitcheryAsset'
    ];
}