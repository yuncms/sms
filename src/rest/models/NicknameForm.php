<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\models;

use Yii;
use yii\base\Model;

/**
 * Class NicknameForm
 * @package yuncms\rest\models
 *
 * @property \yuncms\user\models\User $user
 */
class NicknameForm extends Model
{
    /**
     * @var String 昵称
     */
    public $nickname;

    /**
     * @var \yuncms\user\models\User
     */
    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nickname'], 'required'],
            ['nickname', 'string'],
        ];
    }

    /**
     * 保存昵称
     *
     * @return boolean
     */
    public function save()
    {
        if ($this->validate() && (bool)$this->getUser()->updateAttributes(['nickname' => $this->nickname])) {
            return $this->getUser();
        }
        return false;
    }

    /*
     * @return User
     */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }
}