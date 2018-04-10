<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\controllers;

use Yii;
use yii\rest\Controller;

/**
 * Class TestController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class TestController extends Controller
{
    /**
     * GET 测试
     * @return array
     */
    public function actionGet()
    {
        return Yii::$app->request->get();
    }

    /**
     * POST 测试
     * @return array
     */
    public function actionPost()
    {
        return [
            'postRaw' => Yii::$app->request->getRawBody(),
            'post' => Yii::$app->request->post()
        ];
    }

    /**
     * Header 测试
     * @return array
     */
    public function actionHeader()
    {
        return Yii::$app->request->headers;
    }

    /**
     * Method 测试
     * @return array
     */
    public function actionMethod()
    {
        return [
            Yii::$app->request->method
        ];
    }

    /**
     * 获取用户IP
     * @return array
     */
    public function actionIp()
    {
        return [
            'userIp' => Yii::$app->request->userIP,

        ];
    }
}