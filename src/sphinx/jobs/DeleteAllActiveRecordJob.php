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
 * Class DeleteAllActiveRecordJob
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class DeleteAllActiveRecordJob extends BaseObject implements RetryableJobInterface
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var array 查询条件
     */
    public $condition;

    /**
     * @var array the parameters (name => value) to be bound to the query.
     */
    public $params = [];

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;
        $class::deleteAll($this->condition, $this->params);
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