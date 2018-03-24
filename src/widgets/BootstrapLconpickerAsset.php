<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\widgets;

use yii\web\AssetBundle;

/**
 * Class IconpIckerAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BootstrapLconpickerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/yuncms/framework/resources/lib/bootstrap-iconpicker';

    /**
     * 发布参数
     *
     * @var array
     */
    public $cssOptions = ['media' => 'screen'];

    /**
     * @inheritdoc
     */
    public $js = [
        'bootstrap-iconpicker/js/iconset/iconset-glyphicon.min.js',
        'bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js',
        'bootstrap-iconpicker/js/bootstrap-iconpicker.min.js',
    ];

    public $css = [
        'bootstrap-iconpicker/css/bootstrap-iconpicker.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yiilab\fontawesome\FontAwesomeAsset',
    ];
}