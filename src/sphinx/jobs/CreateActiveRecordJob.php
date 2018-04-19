<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\sphinx\jobs;

use yii\base\BaseObject;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;
use yuncms\sphinx\ActiveRecord;

/**
 * Class CreateActiveRecordJob
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class CreateActiveRecordJob extends BaseObject implements RetryableJobInterface
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var array
     */
    public $attributes;

    /**
     * @var bool 是否执行属性验证
     */
    public $runValidation = true;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;
        $class::create($this->attributes,$this->runValidation);
    }

    /**
     * @return int time to reserve in seconds
     */
    public function getTtr()
    {
        return 60;
    }

    /**
     * @param int $attempt number
     * @param \Exception|\Throwable $error from last execute of the job
     * @return bool
     */
    public function canRetry($attempt, $error)
    {
        return $attempt < 3;
    }
}