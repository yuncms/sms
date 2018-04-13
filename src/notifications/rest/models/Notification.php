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
    public function getReceiver()
    {
        return $this->hasOne(User::class, ['id' => 'receiver']);
    }

    /**
     * 获取任务对象实体
     * @return \yii\db\ActiveQuery
     */
    public function getEntity()
    {
        return $this->hasOne(NotificationEntity::class, ['id' => 'entity_id']);
    }

    /**
     * 获取原有任务对象实体
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(NotificationEntity::class, ['id' => 'source_id']);
    }

    /**
     * 获取目标对象实体
     */
    public function getTarget()
    {
        return $this->hasOne(NotificationEntity::class, ['id' => 'target_id']);
    }
}