<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications\rest\models;

use yuncms\rest\models\User;

/**
 * Class Notification
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Notification extends \yuncms\notifications\models\Notification
{
    /**
     * 获取接收者实例
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}