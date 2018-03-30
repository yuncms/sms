<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\jobs;

use yii\base\BaseObject;
use yii\imagine\Image;
use yii\queue\RetryableJobInterface;
use yuncms\helpers\AvatarHelper;
use yuncms\helpers\FileHelper;
use yuncms\user\models\User;

/**
 * Class SocialAvatarDownloadJob
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 1.0
 */
class SocialAvatarDownloadJob extends BaseObject implements RetryableJobInterface
{

    /**
     * @var int user id
     */
    public $user_id;

    /**
     * @var string 微信头像地址
     */
    public $faceUrl;

    /**
     * 下载头像并保存
     * @param \yii\queue\Queue $queue
     * @throws \League\Flysystem\FileExistsException
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        if (($user = $this->getUser()) != null) {
            $originalImage = AvatarHelper::getAvatarPath($user->id) . '_avatar.jpg';
            //下载图片
            if (($image = @file_get_contents($this->faceUrl)) != false) {
                //保存原图
                file_put_contents($originalImage, $image);
                AvatarHelper::save($user, $originalImage);
            };
        }
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return User::findOne(['id' => $this->user_id]);
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