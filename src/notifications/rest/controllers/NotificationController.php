<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\rest\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yuncms\notifications\rest\models\Notification;
use yuncms\rest\Controller;

/**
 * Class NotificationController
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class NotificationController extends Controller
{
    /**
     * Declares the allowed HTTP verbs.
     * Please refer to [[VerbFilter::actions]] on how to declare the allowed verbs.
     * @return array the allowed HTTP verbs.
     */
    protected function verbs()
    {
        return array_merge(parent::verbs(), [
            'index' => ['GET'],
            'read-all' => ['POST'],
            'unread-notifications' => ['GET'],
        ]);
    }

    /**
     * 显示通知首页
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionIndex()
    {
        $query = Notification::find()->where(['receiver_id' => Yii::$app->user->getId()]);
        return Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
    }

    /**
     * 获取通知详情
     * @param integer $id
     * @return Notification
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $model->setRead();
        return $model;
    }

    /**
     * 标记通知未已读
     * @return void
     */
    public function actionReadAll()
    {
        Notification::setReadAll(Yii::$app->user->getId());
        Yii::$app->getResponse()->setStatusCode(200);
    }

    /**
     * 未读通知数目
     * @return array
     * @throws \Exception|\Throwable
     */
    public function actionUnreadNotifications()
    {
        $total = Notification::getDb()->cache(function ($db) {
            return Notification::find()->where(['user_id' => Yii::$app->user->id])->pending()->count();
        }, 60);
        return ['total' => $total];
    }

    /**
     * 获取通知详情
     * @param string $id
     * @return Notification
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if (($model = Notification::findOne(['id' => $id, 'user_id' => Yii::$app->user->id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException("Notification not found.");
        }
    }
}