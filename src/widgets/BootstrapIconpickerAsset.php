<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\widgets;

use yii\web\AssetBundle;

/**
 * Class BootstrapIconpickerAsset
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class BootstrapIconpickerAsset extends AssetBundle
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
        'js/iconset/iconset-glyphicon.min.js',
        'js/iconset/iconset-fontawesome-4.7.0.min.js',
        'js/bootstrap-iconpicker.js',
    ];

    public $css = [
        'css/bootstrap-iconpicker.min.css',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
        'yuncms\assets\FontAwesomeAsset',
    ];
}