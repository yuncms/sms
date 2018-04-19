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
 * Class updateActiveRecordAllCountersJob
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UpdateActiveRecordAllCountersJob extends BaseObject implements RetryableJobInterface
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var array|string 查询条件
     */
    public $condition;

    /**
     * @var array
     */
    public $counters;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @throws \yii\base\NotSupportedException
     */
    public function execute($queue)
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;
        $class::updateAllCounters($this->counters, $this->condition);
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