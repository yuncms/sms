<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\base\Model;

/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 */
class RegistrationForm extends Model
{
    /**
     * @var string User email address
     */
    public $email;

    /**
     * @var string name
     */
    public $nickname;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var string 验证码
     */
    public $verifyCode;

    /**
     * @var bool 是否同意注册协议
     */
    public $registrationPolicy;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // nickname rules
            'nicknameRequired' => ['nickname', 'required'],
            'nicknameLength' => ['nickname', 'string', 'min' => 3, 'max' => 255],
            'nicknameTrim' => ['nickname', 'filter', 'filter' => 'trim'],
            'nicknamePattern' => ['nickname', 'match', 'pattern' => User::$nicknameRegexp],

            // email rules
            'emailRequired' => ['email', 'required'],
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailPattern' => ['email', 'email', 'checkDNS' => true],
            'emailUnique' => ['email', 'unique', 'targetClass' => User::class, 'message' => Yii::t('yuncms', 'This email address has already been taken')],

            // password rules
            'passwordRequired' => ['password', 'required', 'skipOnEmpty' => Yii::$app->settings->get('enableGeneratingPassword','user')],
            'passwordLength' => ['password', 'string', 'min' => 6],

            // verifyCode needs to be entered correctly
            'verifyCodeRequired' => ['verifyCode', 'required',
                'skipOnEmpty' => !Yii::$app->settings->get('enableRegistrationCaptcha','user')],

            'verifyCode' => ['verifyCode', 'captcha',
                'captchaAction' => '/user/registration/captcha',
                'skipOnEmpty' => !Yii::$app->settings->get('enableRegistrationCaptcha','user')
            ],

            'registrationPolicyRequired' => ['registrationPolicy', 'required', 'skipOnEmpty' => false, 'requiredValue' => true,
                'message' => Yii::t('yuncms', 'By registering you confirm that you accept the Service Agreement and Privacy Policy.'),],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('yuncms', 'Email'),
            'nickname' => Yii::t('yuncms', 'Nickname'),
            'password' => Yii::t('yuncms', 'Password'),
            'verifyCode' => Yii::t('yuncms', 'Verification Code'),
            'registrationPolicy' => Yii::t('yuncms', 'Agree and accept Service Agreement and Privacy Policy'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'register';
    }

    /**
     * Registers a new user account. If registration was successful it will set flash message.
     *
     * @return boolean
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $user = new User();
        $user->setScenario(User::SCENARIO_EMAIL_REGISTER);
        $this->loadAttributes($user);
        if (!$user->register()) {
            return false;
        }
        Yii::$app->session->setFlash('info', Yii::t('yuncms', 'Your account has been created and a message with further instructions has been sent to your email'));
        return Yii::$app->getUser()->login($user, Yii::$app->settings->get('rememberFor','user'));
    }

    /**
     * Loads attributes to the user model. You should override this method if you are going to add new fields to the
     * registration form. You can read more in special guide.
     *
     * By default this method set all attributes of this model to the attributes of User model, so you should properly
     * configure safe attributes of your User model.
     *
     * @param User $user
     */
    protected function loadAttributes(User $user)
    {
        $user->setAttributes($this->attributes);
    }
}
