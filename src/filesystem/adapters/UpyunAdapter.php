<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem\adapters;

use Yii;
use yii\base\InvalidConfigException;
use yuncms\filesystem\FilesystemAdapter;

/**
 * 又拍云接口
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class UpyunAdapter extends FilesystemAdapter
{

    public $accessId;
    public $accessSecret;
    public $bucket;
    public $domain;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->accessId === null) {
            throw new InvalidConfigException('The "accessId" property must be set.');
        }

        if ($this->accessSecret === null) {
            throw new InvalidConfigException('The "accessSecret" property must be set.');
        }

        if ($this->bucket === null) {
            throw new InvalidConfigException('The "bucket" property must be set.');
        }
        if ($this->domain === null) {
            throw new InvalidConfigException('The "domain" property must be set.');
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Yii::t('yuncms', 'Upyun');
    }

    /**
     * 准备适配器
     * @return \JellyBool\Flysystem\Upyun\UpyunAdapter
     */
    protected function createDriver()
    {
        return new \JellyBool\Flysystem\Upyun\UpyunAdapter($this->accessId, $this->accessSecret, $this->bucket, $this->domain);
    }
}
