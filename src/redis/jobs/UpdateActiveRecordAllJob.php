<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\redis\jobs;

use yii\base\BaseObject;
use yii\queue\Queue;
use yuncms\redis\ActiveRecord;
use yii\queue\RetryableJobInterface;

/**
 * Class UpdateActiveRecordAll
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UpdateActiveRecordAllJob extends BaseObject implements RetryableJobInterface
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
    public $attributes;


    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;
        $class::updateAll($this->attributes, $this->condition);
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