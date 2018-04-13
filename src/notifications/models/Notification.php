<?php

namespace yuncms\notifications\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yuncms\db\ActiveRecord;

/**
 * This is the model class for table "{{%notification}}".
 *
 * @property string $id Id
 * @property string $verb Verb
 * @property string $template Template
 * @property int $is_read Read
 * @property int $is_pending Pending
 * @property int $sender_id Sender Id
 * @property string $sender_class Sender Class
 * @property string $receiver Receiver
 * @property int $publish_at Publish At
 * @property string $entity Entity
 * @property string $source Source
 * @property string $target Target
 */
class Notification extends \yuncms\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['publish_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sender_id'], 'integer'],
            [['verb'], 'string', 'max' => 32],
            [['template', 'sender_class', 'receiver', 'entity', 'source', 'target'], 'string', 'max' => 255],
            [['is_read', 'is_pending'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'Id'),
            'verb' => Yii::t('yuncms', 'Verb'),
            'template' => Yii::t('yuncms', 'Template'),
            'is_read' => Yii::t('yuncms', 'Read'),
            'is_pending' => Yii::t('yuncms', 'Pending'),
            'sender_id' => Yii::t('yuncms', 'Sender Id'),
            'sender_class' => Yii::t('yuncms', 'Sender Class'),
            'receiver' => Yii::t('yuncms', 'Receiver'),
            'publish_at' => Yii::t('yuncms', 'Publish At'),
            'entity' => Yii::t('yuncms', 'Entity'),
            'source' => Yii::t('yuncms', 'Source'),
            'target' => Yii::t('yuncms', 'Target'),
        ];
    }

    /**
     * 获取发送者
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne($this->sender_class, ['id' => 'sender_id']);
    }

    /**
     * @inheritdoc
     * @return NotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NotificationQuery(get_called_class());
    }
}
