<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin\models;

use yuncms\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Class AdminLoginAttempt
 *
 * @property integer $id
 * @property string $key
 * @property integer $amount
 * @property integer $reset_at
 * @property integer
 * @property integer
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class AdminLoginAttempt extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_login_attempt}}';
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
            [['key'], 'required'],
        ];
    }

    /**
     * 获取
     * @param string $key
     * @return array|null|ActiveRecord|AdminLoginAttempt
     */
    public static function findByKey($key)
    {
        return static::find()->where(['key' => $key])->andWhere(['>', 'reset_at', time()])->one();
    }
}