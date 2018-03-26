<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web\assets\inspinia;

use yii\web\AssetBundle;

/**
 * Class InspiniaAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class InspiniaAsset extends AssetBundle
{
    /**
     * @inherit
     */
    public $sourcePath = __DIR__ . '/dist';

    public $css = [
        'css/animate.css',
        'css/style.css'
    ];

    /**
     * @inherit
     */
    public $js = [
        'js/plugins/metisMenu/jquery.metisMenu.js',
        'js/plugins/slimscroll/jquery.slimscroll.min.js',
        'js/inspinia.js',
        'js/plugins/pace/pace.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'yuncms\widgets\FontAwesomeAsset',
        'yii\web\YiiAsset',
    ];
}