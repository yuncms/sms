<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin\controllers;


use Yii;
use yuncms\web\Controller;

/**
 * Class VolumesController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class VolumesController extends Controller
{
    /**
     * Shows the asset volume list.
     *
     * @return Response
     */
    public function actionIndex(): Response
    {
        $variables = [];
        $variables['volumes'] = Yii::$app->getFilesystem()->getFilesystems();

        print_r($variables);exit;
        return $this->renderTemplate('settings/assets/volumes/_index', $variables);
    }
}