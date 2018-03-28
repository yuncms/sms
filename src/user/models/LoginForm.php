<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\base\Model;
use yuncms\helpers\PasswordHelper;
use yuncms\user\behaviors\LoginAttemptBehavior;

/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 */
class LoginForm extends Model
{
    /**
     * @var string User's email or mobile
     */
    public $login;

    /**
     * @var string User's plain password
     */
    public $password;

    /**
     * @var bool Whether to remember the user
     */
    public $rememberMe = true;

    /**
     * @var User
     */
    protected $user;

    /**
     * 多次尝试拦截
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => LoginAttemptBehavior::class,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => Yii::t('yuncms', 'Login'),
            'password' => Yii::t('yuncms', 'Password'),
            'rememberMe' => Yii::t('yuncms', 'Remember me next time'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'requiredFields' => [['login', 'password'], 'required'],
            'loginTrim' => ['login', 'trim'],
            'passwordValidate' => ['password', 'validatePassword'],
            'confirmationValidate' => ['login', 'confirmationValidate'],
            'rememberMe' => ['rememberMe', 'boolean'],
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
            if ($this->user === null || !PasswordHelper::validate($this->password, $this->user->password_hash)) {
                $this->addError($attribute, Yii::t('yuncms', 'Invalid login or password'));
            }
        }
    }

    /**
     * Validates the confirmation.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function confirmationValidate($attribute)
    {
        if (!$this->hasErrors()) {
            if ($this->user !== null) {
                $confirmationRequired = Yii::$app->settings->get('enableConfirmation', 'user') && !Yii::$app->settings->get('enableUnconfirmedLogin', 'user');
                if ($confirmationRequired && !$this->user->isEmailConfirmed) {
                    $this->addError($attribute, Yii::t('yuncms', 'You need to confirm your email address.'));
                }
                if ($this->user->isBlocked) {
                    $this->addError($attribute, Yii::t('yuncms', 'Your account has been blocked.'));
                }
            }
        }
    }

    /**
     * Validates form and logs the user in.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            UserLoginHistory::createAsync(['user_id' => $this->user->getId(), 'ip' => Yii::$app->request->userIP]);
            return Yii::$app->user->login($this->user, $this->rememberMe ? Yii::$app->settings->get('rememberFor', 'user') : 0);
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->user = User::findByEmailOrMobile($this->login);
            return true;
        } else {
            return false;
        }
    }
}
