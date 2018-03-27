<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user;

use Yii;
use yii\base\BootstrapInterface;
use yuncms\web\Application;

/**
 * Class Bootstrap
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * 初始化
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        //监听用户活动时间
        /** @var \yii\web\UserEvent $event */
        $app->on(Application::EVENT_AFTER_REQUEST, function ($event) use ($app) {
            if (!$app->user->isGuest && Yii::$app->has('queue')) {
                //$app->user->identity->update
                //记录最后活动时间
                Yii::$app->queue->push(new LastVisitJob(['user_id' => $app->user->identity->id, 'time' => time()]));
            }
        });

        //监听用户登录事件
        /** @var \yii\web\UserEvent $event */
        $app->user->on(User::EVENT_AFTER_LOGIN, function ($event) use ($app) {
            //记录最后登录时间记录最后登录IP记录登录次数
            Yii::$app->queue->push(new ResetLoginDataJob(['user_id' => $app->user->identity->getId(), 'ip' => Yii::$app->request->userIP]));
        });
    }
}