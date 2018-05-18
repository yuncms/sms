<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\models;

use Yii;
use yii\base\Model;
use yuncms\validators\MobileValidator;

/**
 * Model for collecting data on password recovery.
 *
 * @property \yuncms\user\Module $module
 */
class UserRecoveryForm extends Model
{
    /**
     * @var string
     */
    public $mobile;

    /**
     * @var string 验证码
     */
    public $verifyCode;

    /**
     * @var string
     */
    public $password;

    /**
     * @var User
     */
    protected $user;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('yuncms', 'Email'),
            'password' => Yii::t('yuncms', 'Password')
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'mobileTrim' => ['mobile', 'filter', 'filter' => 'trim'],
            'mobileRequired' => ['mobile', 'required'],
            'mobilePattern' => ['mobile', MobileValidator::class],
            'mobileExist' => ['mobile', 'exist', 'targetClass' => User::class, 'message' => Yii::t('yuncms', 'There is no user with this mobile')],

            'passwordRequired' => ['password', 'required'],
            'passwordLength' => ['password', 'string', 'min' => 6],

            // verifyCode needs to be entered correctly
            'verifyCodeRequired' => ['verifyCode', 'required'],
            'verifyCodeString' => ['verifyCode', 'string', 'min' => 5, 'max' => 7],
            'verifyCodeValidator' => ['verifyCode',
                'yuncms\sms\captcha\CaptchaValidator',
                'captchaAction' => '/sms/verify-code',
                'skipOnEmpty' => false,
                'message' => Yii::t('yuncms', 'Phone verification code input error.')
            ],
        ];
    }

    /**
     * 重置密码
     * @return boolean
     */
    public function resetPassword()
    {
        if ($this->validate() && ($user = $this->getUser()) != null) {
            $user->resetPassword($this->password);
            return true;
        }
        return false;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return User::findByMobile($this->mobile);
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'recovery-form';
    }
}
