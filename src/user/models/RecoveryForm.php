<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yuncms\base\Model;
use yuncms\helpers\ArrayHelper;


/**
 * Model for collecting data on password recovery.
 *
 * @property \yuncms\user\Module $module
 */
class RecoveryForm extends Model
{
    /**
     * @var string
     */
    public $email;
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
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, ['request' => ['email'], 'reset' => ['password']]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'emailExist' => ['email', 'exist', 'targetClass' => User::class, 'message' => Yii::t('yuncms', 'There is no user with this email address')],
            'emailUnconfirmed' => ['email', function ($attribute) {
                $this->user = User::findByEmail($this->email);
                if ($this->user !== null && $this->getSetting('enableConfirmation') && !$this->user->isEmailConfirmed) {
                    $this->addError($attribute, Yii::t('yuncms', 'You need to confirm your email address.'));
                }
            }],
            'passwordRequired' => ['password', 'required'],
            'passwordLength' => ['password', 'string', 'min' => 6]
        ];
    }

    /**
     * Sends recovery message.
     *
     * @return boolean
     */
    public function sendRecoveryMessage()
    {
        if ($this->validate()) {
            /** @var UserToken $token */
            $token = new UserToken([ 'user_id' => $this->user->id, 'type' => UserToken::TYPE_RECOVERY]);
            $token->save(false);
            $this->sendMessage($this->user->email,Yii::t('yuncms', 'Complete password reset on {0}', Yii::$app->name),'recovery',['user' => $this->user, 'token' => $token]);
            Yii::$app->session->setFlash('info', Yii::t('yuncms', 'An email has been sent with instructions for resetting your password'));
            return true;
        }
        return false;
    }

    /**
     * Resets user's password.
     *
     * @param UserToken $token
     *
     * @return boolean
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function resetPassword(UserToken $token)
    {
        if (!$this->validate() || $token->user === null) {
            return false;
        }
        if ($token->user->resetPassword($this->password)) {
            Yii::$app->session->setFlash('success', Yii::t('yuncms', 'Your password has been changed successfully.'));
            $token->delete();
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('yuncms', 'An error occurred and your password has not been changed. Please try again later.'));
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'recovery-form';
    }
}
