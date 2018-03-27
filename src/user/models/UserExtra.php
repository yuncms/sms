<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use yuncms\db\ActiveRecord;

/**
 * This is the model class for table "{{%user_extra}}".
 *
 * @property integer $user_id 用户ID
 * @property string $login_ip 登录IP
 * @property integer $login_at 登录时间
 * @property integer $login_num 登录次数
 * @property integer $views 空间查看次数
 * @property integer $supports 赞次数
 * @property integer $followers 关注次数
 * @property integer $last_visit 最后活动时间
 *
 * @property User $user 用户对象
 * @property UserProfile $profile 个人资料对象
 *
 */
class UserExtra extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_extra}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'login_at', 'login_num', 'views', 'supports', 'followers', 'last_visit'], 'integer'],
            [['login_ip'], 'string', 'max' => 255],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('yuncms', 'User ID'),
            'login_ip' => Yii::t('yuncms', 'Login Ip'),
            'login_at' => Yii::t('yuncms', 'Login At'),
            'login_num' => Yii::t('yuncms', 'Login Num'),
            'views' => Yii::t('yuncms', 'Views'),
            'supports' => Yii::t('yuncms', 'Supports'),
            'followers' => Yii::t('yuncms', 'Followers'),
            'last_visit' => Yii::t('yuncms', 'Last Visit'),
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
    public function getProfile()
    {
        return $this->hasOne(UserProfile::class, ['user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return UserExtraQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserExtraQuery(get_called_class());
    }

    /**
     * 获取指定字段排行榜
     * @param string $field 字段
     * @param int $limit
     * @return UserExtra[]
     */
    public static function top($field, $limit)
    {
        return static::find()->with('user')->with('profile')->orderBy([$field => SORT_DESC, 'last_visit' => SORT_DESC])->limit($limit)->all();
    }
}
