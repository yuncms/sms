<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\assets;

use yii\web\AssetBundle;

/**
 * Class InspiniaAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class CPAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = '@vendor/yuncms/framework/resources/assets/cp';

    public $css = [
        'css/inspinia.css'
    ];

    /**
     * @inherit
     */
    public $js = [
        'js/inspinia.min.js'
    ];

    public $depends = [
        'yuncms\assets\AnimateCssAsset',
        'yuncms\assets\JqueryMetisMenuAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yuncms\assets\FontAwesomeAsset',
        'yuncms\assets\JquerySlimScroll',
        'yii\web\YiiAsset'
    ];
}