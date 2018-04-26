<?php

namespace yuncms\notifications\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yuncms\behaviors\JsonBehavior;
use yuncms\db\ActiveRecord;
use yuncms\validators\JsonValidator;

/**
 * This is the model class for table "{{%notifications}}".
 *
 * @property string $id
 * @property string $verb
 * @property integer $notifiable_id
 * @property string $notifiable_class
 * @property string $data
 * @property integer $read_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class DatabaseNotification extends ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notifications}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class
        ];
        $behaviors['data'] = [
            'class' => JsonBehavior::class,
            'attributes' => ['data'],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['notifiable_id', 'notifiable_class'], 'required'],
            [['notifiable_id', 'read_at'], 'integer'],
            [['verb'], 'string', 'max' => 32],
            [['notifiable_class'], 'string', 'max' => 255],
            [['data'], JsonValidator::class],
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
            'notifiable_id' => Yii::t('yuncms', 'Entity'),
            'notifiable_class' => Yii::t('yuncms', 'Entity'),
            'data' => Yii::t('yuncms', 'Data'),
            'read_at' => Yii::t('yuncms', 'Read At'),
            'created_at' => Yii::t('yuncms', 'Created At'),
            'updated_at' => Yii::t('yuncms', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return DatabaseNotificationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DatabaseNotificationQuery(get_called_class());
    }
}
