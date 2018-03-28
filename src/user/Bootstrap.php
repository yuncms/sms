<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user;

use Yii;
use yii\base\BootstrapInterface;
use yii\web\User;
use yuncms\user\models\UserExtra;
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
     * @param \yii\base\Application|\yuncms\web\Application $app
     */
    public function bootstrap($app)
    {
        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            //监听用户活动时间
            /** @var \yii\web\UserEvent $event */
            $app->on(Application::EVENT_AFTER_REQUEST, function ($event) use ($app) {
                if (!$app->user->isGuest && Yii::$app->has('queue')) {
                    $user = UserExtra::findOne(['user_id' => $app->user->id]);
                    $user->updateAttributesAsync(['last_visit' => time()]);
                }
            });

            //监听用户登录事件
            /** @var \yii\web\UserEvent $event */
            $app->user->on(User::EVENT_AFTER_LOGIN, function ($event) use ($app) {
                //记录最后登录时间记录最后登录IP记录登录次数
                $user = UserExtra::findOne(['user_id' => $app->user->id]);
                $user->updateAttributesAsync(['login_at' => time(), 'login_ip' => Yii::$app->request->userIP]);
                $user->updateCountersAsync(['login_num' => 1]);
            });
        }
    }
}