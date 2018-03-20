<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\jobs;

use yii\base\BaseObject;
use yii\db\StaleObjectException;
use yii\queue\Queue;
use yuncms\db\ActiveRecord;

/**
 * Class DeleteActiveRecordJob
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class DeleteActiveRecordJob extends BaseObject implements RetryableJobInterface
{
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
     * @throws StaleObjectException
     * @throws \Exception
     * @throws \Throwable
     */
    public function execute($queue)
    {
        $this->getModel()->delete();
    }

    /**
     * 获取模型实例
     * @return ActiveRecord|null
     */
    public function getModel()
    {
        /**
         * @var ActiveRecord $modelName
         */
        $modelName = $this->modelName;
        return $modelName::findOne($this->condition);
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