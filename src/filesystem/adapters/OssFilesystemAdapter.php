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
 * Class OSSAdapter
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class OssFilesystemAdapter extends FilesystemAdapter
{
    public $accessId;
    public $accessSecret;
    public $bucket;
    public $endpoint;
    public $timeout = 3600;
    public $connectTimeout = 10;

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
        if ($this->endpoint === null) {
            throw new InvalidConfigException('The "endpoint" property must be set.');
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Yii::t('yuncms', 'Aliyun OSS');
    }

    /**
     * @return \Xxtime\Flysystem\Aliyun\OssAdapter
     * @throws \Exception
     */
    protected function prepareAdapter()
    {
        return new \Xxtime\Flysystem\Aliyun\OssAdapter([
            'access_id' => $this->accessId,
            'access_secret' => $this->accessSecret,
            'bucket' => $this->bucket,
            'endpoint' => $this->endpoint,
            'timeout' => $this->timeout,
            'connectTimeout' => $this->connectTimeout,
        ]);
    }

}
