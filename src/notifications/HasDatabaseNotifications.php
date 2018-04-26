<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\notifications;

use yii\db\ActiveQuery;
use yuncms\notifications\models\DatabaseNotification;

/**
 * Trait HasDatabaseNotifications
 * @method ActiveQuery hasMany($class, array $link) see [[BaseActiveRecord::hasMany()]] for more info
 * @method ActiveQuery hasOne($class, array $link) see [[BaseActiveRecord::hasOne()]] for more info
 *
 * @package yuncms\notifications
 */
trait HasDatabaseNotifications
{
    /**
     * Get the entity's notifications.
     * @return ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(DatabaseNotification::class, ['notifiable_id' => 'id'])->onCondition(['notifiable_class' => get_called_class()])->addOrderBy(['created_at' => SORT_DESC]);
    }

    /**
     * 获取实体已经读取通知
     */
    public function readNotifications()
    {
        return $this->getNotifications()->andWhere(['read_at' => null]);
    }

    /**
     * 获取实体未读取通知
     * @return ActiveQuery
     */
    public function unreadNotifications()
    {
        return $this->getNotifications()->andWhere(['NOT', ['read_at' => null]]);
    }
}