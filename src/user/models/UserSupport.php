<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yuncms\db\ActiveRecord;
use yuncms\notifications\contracts\NotificationInterface;
use yuncms\notifications\NotificationTrait;

/**
 * This is the model class for table "{{%support}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $model_id
 * @property string $model_class
 * @property integer $created_at
 * @property integer $updated_at
 * @property ActiveRecord $source
 *
 * @property User $user
 *
 */
class UserSupport extends ActiveRecord implements NotificationInterface
{
    use NotificationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%support}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'model_id'], 'required'],
            [['user_id', 'model_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'ID'),
            'user_id' => Yii::t('yuncms', 'User ID'),
            'model_id' => Yii::t('yuncms', 'Model ID'),
            'model_class' => Yii::t('yuncms', 'Model Class'),
            'created_at' => Yii::t('yuncms', 'Created At'),
            'updated_at' => Yii::t('yuncms', 'Updated At'),
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
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne($this->model_class, ['id' => 'model_id']);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            $this->source->updateCountersAsync(['supports' => 1]);
            try {
                Yii::$app->notification->send($this->source->user, $this);
            } catch (InvalidConfigException $e) {
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $this->source->updateCountersAsync(['supports' => -1]);
        parent::afterDelete();
    }

    /**
     * @inheritdoc
     * @return UserSupportQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserSupportQuery(get_called_class());
    }
}
