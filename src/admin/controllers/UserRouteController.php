<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yuncms\web\Response;
use yuncms\web\Controller;
use yuncms\user\models\UserRoute;

/**
 * Description of RuleController
 */
class UserRouteController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['post'],
                    'assign' => ['post'],
                    'remove' => ['post'],
                    'refresh' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Route models.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $model = new UserRoute();
        return $this->render('index', ['routes' => $model->getRoutes()]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        $routes = Yii::$app->getRequest()->post('route', '');
        $routes = preg_split('/\s*,\s*/', trim($routes), -1, PREG_SPLIT_NO_EMPTY);
        $model = new UserRoute();
        $model->addNew($routes);
        return $model->getRoutes();
    }

    /**
     * Assign routes
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAssign()
    {
        $routes = Yii::$app->getRequest()->post('routes', []);
        $model = new UserRoute();
        $model->addNew($routes);
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return $model->getRoutes();
    }

    /**
     * Remove routes
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRemove()
    {
        $routes = Yii::$app->getRequest()->post('routes', []);
        $model = new UserRoute();
        $model->remove($routes);
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return $model->getRoutes();
    }

    /**
     * Refresh cache
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRefresh()
    {
        $model = new UserRoute();
        $model->invalidate();
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;
        return $model->getRoutes();
    }
}
