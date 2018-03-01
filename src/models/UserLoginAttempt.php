<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\models;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class UserLoginAttempt
 *
 * @property integer $id
 * @property string $username
 * @property integer $amount
 * @property integer $reset_at
 * @property integer
 * @property integer
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UserLoginAttempt extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%login_attempt}}';
    }

    public function behaviors()
    {
        return [
            'timestampBehavior' => TimestampBehavior::class
        ];
    }

    public function rules()
    {
        return [
            [['username'], 'required'],
        ];
    }

    /**
     * 获取
     * @param string $key
     * @return array|null|ActiveRecord|UserLoginAttempt
     */
    public static function findByKey($key)
    {
        return static::find()->where(['key' => $key])->andWhere(['>', 'reset_at', time()])->one();
    }
}