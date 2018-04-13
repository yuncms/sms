<?php

namespace yuncms\notifications\models;

/**
 * This is the ActiveQuery class for [[NotificationEntity]].
 *
 * @see NotificationEntity
 */
class NotificationEntityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return NotificationEntity[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return NotificationEntity|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
