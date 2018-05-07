<?php

namespace yuncms\models;

/**
 * This is the ActiveQuery class for [[Volume]].
 *
 * @see Volume
 */
class VolumeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /*public function active()
    {
        return $this->andWhere(['status' => Volume::STATUS_PUBLISHED]);
    }*/

    /**
     * @inheritdoc
     * @return Volume[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Volume|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
