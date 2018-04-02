<?php

namespace yuncms\trade\models;

use Yii;
use yii\db\Query;
use yuncms\db\ActiveRecord;

/**
 * This is the model class for table "{{%trade_refunds}}".
 *
 * @property int $id
 * @property int $amount
 * @property int $succeed
 * @property string $status
 * @property int $time_succeed
 * @property string $description
 * @property string $failure_code
 * @property string $failure_msg
 * @property int $charge_id
 * @property string $charge_order_no
 * @property string $transaction_no
 * @property string $funding_source
 * @property int $created_at
 *
 * @property TradeCharges $charge
 */
class TradeRefunds extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%trade_refunds}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'description', 'charge_id'], 'required'],
            [['amount', 'succeed', 'time_succeed', 'charge_id'], 'integer'],
            [['status'], 'string', 'max' => 10],
            [['description', 'failure_code', 'failure_msg'], 'string', 'max' => 255],
            [['charge_order_no', 'transaction_no'], 'string', 'max' => 64],
            [['funding_source'], 'string', 'max' => 20],
            [['charge_id'], 'exist', 'skipOnError' => true, 'targetClass' => TradeCharges::class, 'targetAttribute' => ['charge_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'ID'),
            'amount' => Yii::t('yuncms', 'Amount'),
            'succeed' => Yii::t('yuncms', 'Succeed'),
            'status' => Yii::t('yuncms', 'Status'),
            'time_succeed' => Yii::t('yuncms', 'Time Succeed'),
            'description' => Yii::t('yuncms', 'Description'),
            'failure_code' => Yii::t('yuncms', 'Failure Code'),
            'failure_msg' => Yii::t('yuncms', 'Failure Msg'),
            'charge_id' => Yii::t('yuncms', 'Charge ID'),
            'charge_order_no' => Yii::t('yuncms', 'Charge Order No'),
            'transaction_no' => Yii::t('yuncms', 'Transaction No'),
            'funding_source' => Yii::t('yuncms', 'Funding Source'),
            'created_at' => Yii::t('yuncms', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCharge()
    {
        return $this->hasOne(TradeCharges::class, ['id' => 'charge_id']);
    }

    /**
     * {@inheritdoc}
     * @return TradeRefundsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TradeRefundsQuery(get_called_class());
    }

    /**
     * 生成交易流水号
     * @return string
     */
    protected function generateId()
    {
        $i = rand(0, 9999);
        do {
            if (9999 == $i) {
                $i = 0;
            }
            $i++;
            $id = time() . str_pad($i, 4, '0', STR_PAD_LEFT);
            $row = (new Query())->from(static::tableName())->where(['id' => $id])->exists();
        } while ($row);
        return $id;
    }
}
