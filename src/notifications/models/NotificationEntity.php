<?php

namespace yuncms\notifications\models;

use Yii;
use yuncms\db\ActiveRecord;

/**
 * This is the model class for table "{{%notification_entity}}".
 *
 * @property int $id
 * @property string $type
 * @property int $entity_id
 * @property string $entity_class
 */
class NotificationEntity extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notification_entity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id', 'entity_class'], 'required'],
            [['entity_id'], 'integer'],
            [['type'], 'string', 'max' => 20],
            [['entity_class'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'ID'),
            'type' => Yii::t('yuncms', 'Type'),
            'entity_id' => Yii::t('yuncms', 'Entity ID'),
            'entity_class' => Yii::t('yuncms', 'Entity Class'),
        ];
    }

    /**
     * @inheritdoc
     * @return NotificationEntityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NotificationEntityQuery(get_called_class());
    }
}
