<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\helpers;

use Yii;

/**
 * Class UserRBACHelper
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UserRBACHelper extends RBACHelper
{
    /**
     * @return \yii\rbac\ManagerInterface|\yuncms\rbac\DbManager
     * @throws \yii\base\InvalidConfigException
     */
    public static function getAuthManager()
    {
        if (Yii::$app instanceof \yuncms\admin\Application) {
            return Yii::$app->getUserAuthManager();
        }
        return Yii::$app->getAuthManager();
    }
}