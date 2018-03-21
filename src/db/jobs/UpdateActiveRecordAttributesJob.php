<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\db\jobs;

use yii\base\BaseObject;
use yii\queue\Queue;
use yuncms\db\ActiveRecord;
use yii\queue\RetryableJobInterface;

/**
 * Class updateAttributesJob
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UpdateActiveRecordAttributesJob extends BaseObject implements RetryableJobInterface
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
     * @var array
     */
    public $attributes;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $model = $this->getModel();
        $model->updateAttributes($this->attributes);
    }

    /**
     * 获取模型实例
     * @return ActiveRecord|null
     */
    public function getModel()
    {
        /** @var ActiveRecord $class */
        $class = $this->modelClass;
        return $class::findOne($this->condition);
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