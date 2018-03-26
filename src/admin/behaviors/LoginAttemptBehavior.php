<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\admin\behaviors;

use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\base\Behavior;
use yuncms\admin\models\AdminLoginAttempt;

/**
 * Class LoginAttemptBehavior
 *
 * @property Model $owner
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class LoginAttemptBehavior extends Behavior
{
    const UNIT_SECOND = 'second';
    const UNIT_MINUTE = 'minute';
    const UNIT_HOUR = 'hour';
    const UNIT_DAY = 'day';
    const UNIT_WEEK = 'week';
    const UNIT_MONTH = 'month';
    const UNIT_YEAR = 'year';

    /**
     * @var int 尝试次数
     */
    public $attempts = 3;

    /**
     * @var int 拦截时间
     */
    public $duration = 5;

    /**
     * @var string 拦截单位
     */
    public $durationUnit = self::UNIT_MINUTE;

    /**
     * @var int 禁用时间
     */
    public $disableDuration = 15;

    /**
     * @var string 禁用时间单位
     */
    public $disableDurationUnit = self::UNIT_MINUTE;

    public $usernameAttribute = 'login';

    public $passwordAttribute = 'password';

    /**
     * @var string 拦截后的提示信息
     */
    public $message;

    /**
     * @var AdminLoginAttempt
     */
    private $_attempt;

    /**
     * @var array 安全单位
     */
    private $_safeUnits = [
        self::UNIT_SECOND => 1,
        self::UNIT_MINUTE => 60,
        self::UNIT_HOUR => 3600,
        self::UNIT_DAY => 86400,
        self::UNIT_WEEK => 604800,
        self::UNIT_MONTH => 2592000,
        self::UNIT_YEAR => 31536000
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yuncms', 'You have exceeded the password attempts.');
        }
        if (!in_array($this->durationUnit, $this->_safeUnits)) {
            throw new Exception($this->durationUnit . " is not an allowed unit.");
        }
        if (!in_array($this->disableDurationUnit, $this->_safeUnits)) {
            throw new Exception($this->disableDurationUnit . " is not an allowed unit.");
        }
    }

    /**
     * @return array
     */
    public function events()
    {
        return [
            Model::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            Model::EVENT_AFTER_VALIDATE => 'afterValidate',
        ];
    }

    /**
     * 验证前检查拦截
     */
    public function beforeValidate()
    {
        if ($this->_attempt = AdminLoginAttempt::findByKey($this->getKey())) {
            if ($this->_attempt->amount >= $this->attempts) {
                $this->owner->addError($this->usernameAttribute, $this->message);
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function afterValidate()
    {
        //判断是否存在密码字段的验证错误信息，如果存在进入断言
        if ($this->owner->hasErrors($this->passwordAttribute)) {
            $attempt = $this->getUserLoginAttempt();
            if ($attempt->amount >= $this->attempts) {//超过最大尝试次数
                $reset_at = time() + ($this->disableDuration * $this->_safeUnits[$this->disableDurationUnit]);
            } else {
                $reset_at = time() + ($this->duration * $this->_safeUnits[$this->durationUnit]);
            }
            $attempt->updateAttributes(['amount' => $attempt->amount += 1, 'reset_at' => $reset_at]);
        }
    }

    /**
     * 获取Key用作索引
     * @return string
     */
    public function getKey()
    {
        return sha1($this->owner->{$this->usernameAttribute});
    }

    /**
     * 获取拦截历史
     * @return AdminLoginAttempt
     */
    public function getUserLoginAttempt()
    {
        if (!$this->_attempt) {
            $this->_attempt = new AdminLoginAttempt;
            $this->_attempt->username = $this->getKey();
        }
        return $this->_attempt;
    }
}