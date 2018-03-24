<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;
use yuncms\db\ActiveRecord;
use yuncms\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $mobile
 * @property string $auth_key
 * @property string $password_hash
 * @property integer $status
 * @property integer $last_login_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class Admin extends ActiveRecord implements IdentityInterface
{
    //场景定义
    const SCENARIO_CREATE = 'create';//创建
    const SCENARIO_UPDATE = 'update';//更新

    const STATUS_ACTIVE = 0b0;
    const STATUS_DELETED = 0b1;

    /**
     * @var string Default username regexp
     */
    public static $usernameRegexp = '/^[-a-zA-Z0-9_\x{4e00}-\x{9fa5}\.@]+$/u';

    public static $mobileRegexp = '/^13[\d]{9}$|^15[\d]{9}$|^17[\d]{9}$|^18[\d]{9}$/';

    /** @var string Plain password. Used for model validation. */
    public $password;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }

    /**
     * 定义行为
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            static::SCENARIO_CREATE => ['username', 'password', 'email', 'mobile'],
            static::SCENARIO_UPDATE => [],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'mobile'], 'required'],

            // username rules
            ['username', 'string', 'min' => 3, 'max' => 50],
            ['username', 'match', 'pattern' => self::$usernameRegexp],
            [['username'], 'unique', 'message' => Yii::t('admin', 'This username has already been taken')],
            ['username', 'trim'],

            // email rules
            [['email'], 'string', 'max' => 60],
            ['email', 'email'],
            [['email'], 'unique', 'message' => Yii::t('admin', 'This email has already been taken')],
            ['email', 'trim'],

            // mobile rules
            [['mobile'], 'string', 'max' => 11],
            ['mobile', 'match', 'pattern' => self::$mobileRegexp],
            [['mobile'], 'unique', 'message' => Yii::t('admin', 'This mobile has already been taken')],

            // password rules
            ['password', 'string', 'min' => 6, 'max' => 255],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('yuncms', 'ID'),
            'username' => Yii::t('yuncms', 'Username'),
            'email' => Yii::t('yuncms', 'EMail'),
            'mobile' => Yii::t('yuncms', 'Mobile'),
            'password' => Yii::t('yuncms', 'Password'),
            'auth_key' => Yii::t('yuncms', 'Auth Key'),
            'password_hash' => Yii::t('yuncms', 'Password Hash'),
            'access_token' => Yii::t('yuncms', 'Access Token'),
            'status' => Yii::t('yuncms', 'Status'),
            'last_login_at' => Yii::t('yuncms', 'Last Login At'),
            'created_at' => Yii::t('yuncms', 'Created At'),
            'updated_at' => Yii::t('yuncms', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     * @throws \yii\base\Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
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
            $this->generateAuthKey();
        }

        if (!empty($this->password)) {
            $this->setPassword($this->password);
        }
        // ...custom code here...
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * 通过邮箱、手机号或用户名获取账户信息
     * @param $emailOrMobileOrUsername
     * @return null|Admin
     */
    public static function findByEmailOrMobileOrUsername($emailOrMobileOrUsername)
    {
        if (filter_var($emailOrMobileOrUsername, FILTER_VALIDATE_EMAIL)) {
            return static::findByEmail($emailOrMobileOrUsername);
        } else if (preg_match(self::$mobileRegexp, $emailOrMobileOrUsername)) {
            return static::findByMobile($emailOrMobileOrUsername);
        } else {
            return static::findByUsername($emailOrMobileOrUsername);
        }
    }

    /**
     * 通过邮箱获取用户
     * @param string $email 邮箱
     * @return null|static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * 通过手机号获取用户
     * @param string $mobile
     * @return static
     */
    public static function findByMobile($mobile)
    {
        return static::findOne(['mobile' => $mobile]);
    }

    /**
     * 通过用户名获取用户
     * @param string $username 用户标识
     * @return null|static
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
}
