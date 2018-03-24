<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\admin\controllers;

use yii\web\Controller;

/**
 * Class SettingController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class SettingController extends Controller
{

    public function actions()
    {
        return [
            //....
            'setting' => [
                'class' => 'yuncms\core\actions\SettingsAction',
                'modelClass' => 'yuncms\admin\models\Settings',
                //'scenario' => 'user',
                //'scenario' => 'site', // Change if you want to re-use the model for multiple setting form.
                'viewName' => 'settings'    // The form we need to render
            ],
            //....
        ];
    }
}