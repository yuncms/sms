<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\rest\controllers;

use Yii;
use yii\data\ActiveDataProvider;
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
}