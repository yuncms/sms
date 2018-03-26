<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin\models;

use Yii;
use yuncms\admin\behaviors\LoginAttemptBehavior;
use yuncms\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{

    /**
     * @var string User's email or mobile
     */
    public $login;

    /**
     * @var string 密码
     */
    public $password;

    /**
     * @var bool 记住我
     */
    public $rememberMe;

    /**
     * @var string 验证码
     */
    public $verifyCode;

    /**
     * 用户组件
     * @var Admin
     */
    private $_user;

//    public function behaviors()
//    {
//        return [
//            [
//                'class' => LoginAttemptBehavior::class,
//            ],
//        ];
//    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['login', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],

            // verifyCode needs to be entered correctly
            'verifyCodeRequired' => ['verifyCode', 'required'],

            'verifyCode' => ['verifyCode', 'captcha', 'captchaAction' => '/admin/security/captcha'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('yuncms', 'Incorrect username or password.'));
            }
        }
    }

    /**
     * Logs in a user using the provided username or email and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]] or [[email]]
     *
     * @return Admin|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::findByEmailOrMobileOrUsername($this->login);
        }
        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => Yii::t('yuncms', 'Account'),
            'password' => Yii::t('yuncms', 'Password'),
            'rememberMe' => Yii::t('yuncms', 'Remember Me'),
            'verifyCode' => Yii::t('yuncms', 'Verify Code'),
        ];
    }
}
