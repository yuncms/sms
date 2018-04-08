<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\models;

use yii\helpers\Url;
use yii\web\Link;
use yii\web\Linkable;

/**
 * Class User
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class User extends \yuncms\user\models\User
{
    /**
     * 扩展字段定义
     * @return array
     */
    public function extraFields()
    {
        return ['profile', 'extra', 'loginHistories', 'socialAccounts', 'tags'];
    }
}