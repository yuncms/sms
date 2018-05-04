<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use yii\helpers\Url;
use yuncms\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_token}}".
 *
 * @property integer $user_id
 * @property string $code
 * @property integer $type
 * @property integer $created_at
 *
 * @property User $user
 *
 * @property-read bool isExpired 是否过期
 */
class UserToken extends ActiveRecord
{
    const TYPE_CONFIRMATION = 0b0;
    const TYPE_RECOVERY = 0b1;
    const TYPE_CONFIRM_NEW_EMAIL = 0b10;
    const TYPE_CONFIRM_OLD_EMAIL = 0b11;
    const TYPE_CONFIRM_NEW_MOBILE = 0b100;
    const TYPE_CONFIRM_OLD_MOBILE = 0b101;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_token}}';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['user_id', 'code', 'type'];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('yuncms', 'User ID'),
            'code' => Yii::t('yuncms', 'Code'),
            'type' => Yii::t('yuncms', 'Type'),
            'created_at' => Yii::t('yuncms', 'Created At'),
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
     * @return boolean Whether token has expired.
     */
    public function getIsExpired()
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
            case self::TYPE_CONFIRM_NEW_EMAIL:
            case self::TYPE_CONFIRM_OLD_EMAIL:
                $expirationTime =Yii::$app->settings->get('confirmWithin','user');
                break;
            case self::TYPE_CONFIRM_NEW_MOBILE:
            case self::TYPE_CONFIRM_OLD_MOBILE:
                $expirationTime = Yii::$app->settings->get('confirmWithin','user');
                break;
            case self::TYPE_RECOVERY:
                $expirationTime = Yii::$app->settings->get('recoverWithin','user');
                break;
            default:
                throw new \RuntimeException();
        }
        return ($this->created_at + $expirationTime) < time();
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
                $route = '/user/registration/confirm';
                break;
            case self::TYPE_RECOVERY:
                $route = '/user/recovery/reset';
                break;
            case self::TYPE_CONFIRM_NEW_EMAIL:
            case self::TYPE_CONFIRM_OLD_EMAIL:
                $route = '/user/setting/confirm';
                break;
            case self::TYPE_CONFIRM_NEW_MOBILE:
            case self::TYPE_CONFIRM_OLD_MOBILE:
                $route = '/user/setting/mobile';
                break;
            default:
                throw new \RuntimeException();
        }

        return Url::to([$route, 'id' => $this->user_id, 'code' => $this->code], true);
    }

    /**
     * @inheritdoc
     * @return UserTokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserTokenQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert) {
            static::deleteAll(['user_id' => $this->user_id, 'type' => $this->type]);
            $this->setAttribute('created_at', time());
            if ($this->type == self::TYPE_CONFIRM_NEW_MOBILE || $this->type == self::TYPE_CONFIRM_OLD_MOBILE) {
                $this->setAttribute('code', Yii::$app->security->generateRandomString(6));
            } else {
                $this->setAttribute('code', Yii::$app->security->generateRandomString());
            }
        }

        return true;
    }
}
