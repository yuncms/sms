<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\jobs;

use yii\base\BaseObject;
use yii\queue\RetryableJobInterface;

/**
 * Class BaseJob
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
abstract class BaseJob extends BaseObject implements RetryableJobInterface
{
    /**
     * @var string|null The configured job description
     */
    public $description;

    /**
     * @var int The current progress
     */
    private $_progress;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // Set the default progress
        $this->_progress = 0;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->description ?? $this->defaultDescription();
    }

    // Protected Methods
    // =========================================================================

    /**
     * Returns a default description for [[getDescription()]].
     *
     * @return string|null
     */
    protected function defaultDescription()
    {
        return null;
    }

    /**
     * Sets the job progress on the queue.
     *
     * @param \yii\queue\Queue|QueueInterface $queue
     * @param float                           $progress A number between 0 and 1
     */
    protected function setProgress($queue, float $progress)
    {
        if ($progress !== $this->_progress && $queue instanceof QueueInterface) {
            $queue->setProgress(round(100 * $progress));
        }
    }
}