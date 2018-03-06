<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\filesystem\adapters;

use yii\base\InvalidConfigException;
use yuncms\filesystem\Adapter;

/**
 * Class OSSAdapter
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class OssAdapter extends Adapter
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