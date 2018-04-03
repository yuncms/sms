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
 * Class UserCollection
 *
 * @property integer $user_id
 * @property integer $model_id
 * @property string $model_class
 * @property string $subject
 * @property integer $created_at
 * @property integer $updated_at
 * @property ActiveRecord $source
 *
 * @property User $user
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UserCollection extends ActiveRecord implements NotificationInterface
{
    use NotificationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_collections}}';
    }

    /**
     * 是否收藏
     * @param string $model
     * @param integer $modelId
     * @param integer $user_id
     * @return bool
     */
    public static function isCollected($model, $modelId, $user_id = null)
    {
        return static::find()->where([
            'user_id' => $user_id ? $user_id : Yii::$app->user->id,
            'model_class' => $model,
            'model_id' => $modelId
        ])->exists();
    }

    /**
     * @inheritdoc
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
            ]];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id'], 'required'],
            [['subject'], 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
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
            $this->source->updateCountersAsync(['collections' => 1]);
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
        $this->source->updateCountersAsync(['collections' => -1]);
        parent::afterDelete();
    }
}