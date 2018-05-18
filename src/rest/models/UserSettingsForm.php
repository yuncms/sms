<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\models;

use Yii;
use yii\base\Model;
use yuncms\helpers\PasswordHelper;

/**
 * SettingsForm gets user's username, email and password and changes them.
 *
 * @property User $user
 */
class UserSettingsForm extends Model
{
    /** @var string 旧密码 */
    public $password;

    /**
     * @var string
     */
    public $new_password;

    /**
     * @var User
     */
    private $_user;

    /**
     * @return User
     */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'passwordValidate' => ['password', 'validatePassword'],
            'password' => ['password', 'string', 'min' => 6],
            'newPasswordLength' => ['new_password', 'string', 'min' => 6],
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
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('yuncms', 'Password'),
            'new_password' => Yii::t('yuncms', 'New password')
        ];
    }

    /**
     * Saves new account settings.
     *
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
            $this->user->scenario = User::SCENARIO_PASSWORD;
            $this->user->password = $this->new_password;
            return $this->user->save();
        }
        return false;
    }
}
