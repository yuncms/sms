<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\admin\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yuncms\web\Controller;
use yuncms\user\models\UserBizRule;
use yuncms\helpers\UserRBACHelper;
use yuncms\admin\models\UserBizRuleSearch;

/**
 * Description of RuleController
 */
class UserRuleController extends Controller
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
        $searchModel = new UserBizRuleSearch();
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
        $model = new UserBizRule(null);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            UserRBACHelper::invalidate();
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms','Create success.'));
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('create', ['model' => $model,]);
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
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            UserRBACHelper::invalidate();
            Yii::$app->getSession()->setFlash('success', Yii::t('yuncms','Update success.'));
            return $this->redirect(['view', 'id' => $model->name]);
        }

        return $this->render('update', ['model' => $model,]);
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
        Yii::$app->authManager->remove($model->item);
        UserRBACHelper::invalidate();
        Yii::$app->getSession()->setFlash('success', Yii::t('yuncms','Delete success.'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return UserBizRule the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\base\InvalidConfigException
     */
    protected function findModel($id)
    {
        $item = UserRBACHelper::getAuthManager()->getRule($id);
        if ($item) {
            return new UserBizRule($item);
        } else {
            throw new NotFoundHttpException(Yii::t('yuncms', 'The requested page does not exist.'));
        }
    }
}