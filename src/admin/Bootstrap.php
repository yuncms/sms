<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin;

use Yii;
use yii\base\BootstrapInterface;
use yuncms\backend\AccessControl;

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
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
        //附加权限验证行为
        $app->attachBehavior('access', Yii::createObject(AccessControl::class));

        //锁定布局
        $app->layout = '@vendor/xutl/yii2-inspinia-widget/views/layouts/main';
    }
}