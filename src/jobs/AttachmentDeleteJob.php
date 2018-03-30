<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\jobs;

use Yii;
use yii\base\BaseObject;
use yii\queue\RetryableJobInterface;
use League\Flysystem\Filesystem;
use yuncms\filesystem\Adapter;

/**
 * Class AttachmentDeleteJob.
 */
class AttachmentDeleteJob extends BaseObject implements RetryableJobInterface
{
    /**
     * @var string 文件路径
     */
    public $path;

    /**
     * @param $queue
     * @throws \League\Flysystem\FileNotFoundException
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        if(self::getVolume()->has($this->path)){
            self::getVolume()->delete($this->path);
        }
    }

    /**
     * 获取头像存储卷
     * @return Adapter|Filesystem
     * @throws \yii\base\InvalidConfigException
     */
    public static function getVolume()
    {
        return Yii::$app->getFilesystem()->get(Yii::$app->settings->get('volume', 'attachment', 'attachment'));
    }

    /**
     * @inheritdoc
     */
    public function getTtr()
    {
        return 60;
    }

    /**
     * @inheritdoc
     */
    public function canRetry($attempt, $error)
    {
        return $attempt < 3;
    }
}