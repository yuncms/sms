<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin;

use Yii;
use yii\rbac\Item;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\base\NotSupportedException;
use yuncms\web\Controller;
use yuncms\user\models\UserAuthItem;
use yuncms\admin\models\UserAuthItemSearch;
use yuncms\helpers\UserRBACHelper;

/**
 * Class ItemController
 * @property integer $type
 */
class UserItemController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'assign' => ['post'],
                    'remove' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $searchModel = new UserAuthItemSearch(['type' => $this->type]);
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = new UserAuthItem(null);
        $model->type = $this->type;
        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms','Create success.'));
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('create', ['model' => $model]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->getUserAuthManager()->remove($model->item);
        UserRBACHelper::invalidate();
        return $this->redirect(['index']);
    }

    /**
     * Assign items
     * @param string $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAssign($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->addChildren($items);
        Yii::$app->getResponse()->format = 'json';
        return array_merge($model->getItems(), ['success' => $success]);
    }

    /**
     * Assign or remove items
     * @param string $id
     * @return array
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionRemove($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->removeChildren($items);
        Yii::$app->getResponse()->format = 'json';
        return array_merge($model->getItems(), ['success' => $success]);
    }

    /**
     * @inheritdoc
     */
    public function getViewPath()
    {
        return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'user-item';
    }

    /**
     * Label use in view
     * @throws NotSupportedException
     */
    public function labels()
    {
        throw new NotSupportedException(get_class($this) . ' does not support labels().');
    }

    /**
     * Type of Auth Item.
     * @return void
     */
    public function getType()
    {

    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return UserAuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\base\InvalidConfigException
     */
    protected function findModel($id)
    {
        $auth = Yii::$app->getUserAuthManager();
        $item = $this->type === Item::TYPE_ROLE ? $auth->getRole($id) : $auth->getPermission($id);
        if ($item) {
            return new UserAuthItem($item);
        } else {
            throw new NotFoundHttpException(Yii::t('yuncms', 'The requested page does not exist.'));
        }
    }
}