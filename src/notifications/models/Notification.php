<?php

namespace yuncms\notifications\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yuncms\db\ActiveRecord;
use yuncms\user\models\User;

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
 * @property string $receiver_id Receiver
 * @property int $publish_at Publish At
 * @property int $entity_id Entity
 * @property int $source_id Source
 * @property int $target_id Target
 */
class Notification extends ActiveRecord
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
            [['sender_id', 'publish_at', 'entity_id', 'source_id', 'target_id'], 'integer'],
            [['publish_at'], 'required'],
            [['verb'], 'string', 'max' => 32],
            [['template', 'sender_class', 'receiver'], 'string', 'max' => 255],
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
            'entity_id' => Yii::t('yuncms', 'Entity'),
            'source_id' => Yii::t('yuncms', 'Source'),
            'target_id' => Yii::t('yuncms', 'Target'),
        ];
    }

    /**
     * 是否已读
     * @return bool
     */
    public function getIsRead()
    {
        return (bool)$this->is_read;
    }

    /**
     * 设置指定用户为全部已读
     * @param int $userId
     * @return int
     */
    public static function setReadAll($userId)
    {
        return self::updateAll(['is_read' => true], ['receiver_id' => $userId]);
    }

    /**
     * 获取接收者实例
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::class, ['id' => 'receiver_id']);
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

    /**
     * @inheritdoc
     * @return NotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NotificationQuery(get_called_class());
    }
}
