<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem\adapters;

use Qcloud\Cos\Client;
use Yii;
use yii\base\InvalidConfigException;
use yuncms\filesystem\Adapter;

class CosV5Adapter extends Adapter
{
    public $appId;
    public $accessId;
    public $accessSecret;
    public $bucket;
    public $domain;
    public $region;
    public $timeout = 60;
    public $connectTimeout = 10;
    /**
     * @var string https://{your-bucket}-{your-app-id}.file.myqcloud.com
     */
    public $cdn = '';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->appId === null) {
            throw new InvalidConfigException('The "appId" property must be set.');
        }
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
        if ($this->region === null) {
            throw new InvalidConfigException('The "region" property must be set.');
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Yii::t('yuncms', 'QCloud COS');
    }

    /**
     * 准备适配器
     * @return \Freyo\Flysystem\QcloudCOSv5\Adapter
     */
    protected function prepareAdapter()
    {
        $config = [
            'region' => $this->region,
            'credentials' => [
                'appId' => $this->appId,
                'secretId' => $this->accessId,
                'secretKey' => $this->accessSecret,
            ],
            'timeout' => $this->timeout,
            'connect_timeout' => $this->timeout,
            'bucket' => $this->bucket,
            'cdn' => $this->cdn,
        ];

        $client = new Client($config);

        return new \Freyo\Flysystem\QcloudCOSv5\Adapter($client, $config);
    }
}