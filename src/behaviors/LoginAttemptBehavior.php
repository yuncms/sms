<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\behaviors;


use Yii;
use yii\base\Model;
use yii\base\Behavior;
use yii\db\Expression;
use yii\helpers\Inflector;
use yuncms\models\UserLoginAttempt;

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

    public $usernameAttribute = 'email';

    public $passwordAttribute = 'password';

    public $message = 'You have exceeded the password attempts.';

    /**
     * @var UserLoginAttempt
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
        if ($this->_attempt = UserLoginAttempt::find()->where(['key' => $this->getKey()])->andWhere(['>', 'reset_at', time()])->one()) {
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
        if ($this->owner->hasErrors($this->passwordAttribute)) {
            if (!$this->_attempt) {
                $this->_attempt = new UserLoginAttempt;
                $this->_attempt->username = $this->getKey();
            }
            $this->_attempt->amount += 1;
            if ($this->_attempt->amount >= $this->attempts)
                $this->_attempt->reset_at = $this->intervalExpression($this->disableDuration, $this->disableDurationUnit);
            else
                $this->_attempt->reset_at = $this->intervalExpression($this->duration, $this->durationUnit);
            $this->_attempt->save();
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
     * @param int $length
     * @param string $unit
     * @return Expression
     * @throws \Exception
     */
    private function intervalExpression(int $length, $unit = self::UNIT_SECOND)
    {
        if (!in_array($unit, $this->_safeUnits)) {
            throw new \Exception("$unit is not an allowed unit.");
        }
        return time() + ($length * $this->_safeUnits[$unit]);
    }
}