<?php

namespace yuncms\notifications\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yuncms\behaviors\JsonBehavior;
use yuncms\db\ActiveRecord;
use yuncms\user\models\User;
use yuncms\validators\JsonValidator;

/**
 * This is the model class for table "{{%notification}}".
 *
 * @property string $id
 * @property integer $user_id
 * @property string $verb
 * @property string $template
 * @property integer $is_read
 * @property integer $is_pending
 * @property string $entity
 * @property integer $publish_at
 *
 * @property User $user
 *
 * @property-read boolean $isAuthor 是否是作者
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
        $behaviors = parent::behaviors();
        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'attributes' => [
                BaseActiveRecord::EVENT_BEFORE_INSERT => ['publish_at'],
            ],
        ];
        $behaviors['entity'] = [
            'class' => JsonBehavior::class,
            'attributes' => ['entity'],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'verb', 'template', 'entity'], 'required'],
            [['user_id'], 'integer'],
            [['verb'], 'string', 'max' => 32],
            [['template'], 'string', 'max' => 255],
            [['is_read', 'is_pending'], 'boolean'],
            [['is_read', 'is_pending'], 'default', 'value' => false],
            [['entity'], JsonValidator::class],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'Id'),
            'user_id' => Yii::t('yuncms', 'User Id'),
            'verb' => Yii::t('yuncms', 'Verb'),
            'template' => Yii::t('yuncms', 'Template'),
            'is_read' => Yii::t('yuncms', 'Read'),
            'is_pending' => Yii::t('yuncms', 'Pending'),
            'entity' => Yii::t('yuncms', 'Entity'),
            'publish_at' => Yii::t('yuncms', 'Publish At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
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
