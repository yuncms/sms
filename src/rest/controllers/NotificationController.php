<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yuncms\notifications\models\DatabaseNotification;
use yuncms\rest\Controller;
use yuncms\rest\models\User;

/**
 * Class NotificationController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class NotificationController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'options' => [
                'class' => 'yii\rest\OptionsAction',
            ],
        ];
    }

    /**
     * 跟用户相关的通知列表
     * @return ActiveDataProvider
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $user = $this->findModel(Yii::$app->user->id);
        $query = $user->getNotifications();
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
            'pagination' => [
                'params' => $requestParams
            ],
            'sort' => [
                'params' => $requestParams
            ],
        ]);
    }

    /**
     * 查看通知详情
     * @param string $id
     * @return DatabaseNotification
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $user = $this->findModel(Yii::$app->user->id);
        /** @var DatabaseNotification $notification */
        if (($notification = $user->getNotifications()->andWhere(['id' => $id])->one()) != null) {
            $notification->setRead();
            return $notification;
        } else {
            throw new NotFoundHttpException("Notification not found: $id");
        }
    }

    /**
     * 获取用户实例
     * @param integer $id
     * @return User
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) != null) {
            return $model;
        } else {
            throw new NotFoundHttpException("User not found: $id");
        }
    }
}