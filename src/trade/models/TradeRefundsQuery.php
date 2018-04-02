<?php

namespace yuncms\trade\models;

/**
 * This is the ActiveQuery class for [[TradeRefunds]].
 *
 * @see TradeRefunds
 */
class TradeRefundsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TradeRefunds[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TradeRefunds|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
