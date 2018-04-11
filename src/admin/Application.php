<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin;

use yuncms\rbac\DbManager;

/**
 * Class Application
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Application extends \yuncms\web\Application
{
    /**
     * Returns the rbac component.
     * @return DbManager the rbac component.
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserAuthManager(): DbManager
    {
        return $this->get('userAuthManager');
    }
}