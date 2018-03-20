<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\jobs;

use yii\base\BaseObject;
use yii\queue\Queue;
use yuncms\db\ActiveRecord;
use yii\queue\RetryableJobInterface;

/**
 * Class DeleteAllActiveRecordJob
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class DeleteAllActiveRecordJob extends BaseObject implements RetryableJobInterface
{
    /**
     * @var array the parameters (name => value) to be bound to the query.
     */
    public $params = [];

    /**
     * @var string
     */
    public $modelName;

    /**
     * @var array 查询条件
     */
    public $condition;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        /**
         * @var ActiveRecord $modelName
         */
        $modelName = $this->modelName;
        $modelName::deleteAll($this->condition, $this->params);
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