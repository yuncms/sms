<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web\controllers;

use Yii;
use yuncms\web\Controller;
use yuncms\web\Response;

/**
 * Class PingController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class HealthController extends Controller
{
    /**
     * Ping
     * @return string
     */
    public function actionPing()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        return 'Pong';
    }
}