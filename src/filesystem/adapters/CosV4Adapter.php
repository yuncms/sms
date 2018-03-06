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
 * Class CosV4Adapter
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class CosV4Adapter extends Adapter
{
    public $protocol = 'http';
    public $appId;
    public $accessId;
    public $accessSecret;
    public $bucket;
    public $domain;
    public $region;
    public $timeout = 60;
    public $debug = false;

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
     * 准备适配器
     * @return \Freyo\Flysystem\QcloudCOSv4\Adapter
     */
    protected function prepareAdapter()
    {
        return new \Freyo\Flysystem\QcloudCOSv4\Adapter([
            'protocol' => $this->protocol,
            'domain' => $this->domain,
            'app_id' => $this->appId,
            'secret_id' => $this->accessId,
            'secret_key' => $this->accessSecret,
            'timeout' => $this->timeout,
            'bucket' => $this->bucket,
            'region' => $this->region,
            'debug' => $this->debug,
        ]);
    }
}