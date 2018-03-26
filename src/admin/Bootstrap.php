<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin;

use Yii;
use yii\base\BootstrapInterface;
use yuncms\filters\BackendAccessControl;

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
     * @param \yuncms\web\Application $app
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
        //附加权限验证行为
        $app->attachBehavior('access', Yii::createObject(BackendAccessControl::class));

        //设置前台URL
        $app->frontUrlManager->baseUrl = Yii::$app->settings->get('url', 'system');
    }
}