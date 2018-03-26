<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
 namespace xutl\inspinia;

 use yii\web\View;
 use yii\web\AssetBundle;

 class PaceAsset extends AssetBundle{
     public $sourcePath = '@yuncms/admin/resources/assets';

     /**
      * 发布参数
      *
      * @var array
      */
     public $jsOptions = [
         'position' => View::POS_HEAD,
         'data-pace-options' => ['restartOnRequestAfter' => true]
     ];

     public $js = [
         'js/plugins/pace/pace.min.js'
     ];
 }