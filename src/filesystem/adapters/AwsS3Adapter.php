<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem\adapters;

use Aws\S3\S3Client;
use Yii;
use yii\base\InvalidConfigException;
use yuncms\filesystem\Adapter;

/**
 * Class AwsS3Adapter
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class AwsS3Adapter extends Adapter
{
    /**
     * @var string
     */
    public $key;
    /**
     * @var string
     */
    public $secret;
    /**
     * @var string
     */
    public $region;
    /**
     * @var string
     */
    public $baseUrl;
    /**
     * @var string
     */
    public $version;
    /**
     * @var string
     */
    public $bucket;
    /**
     * @var string|null
     */
    public $prefix;
    /**
     * @var array
     */
    public $options = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->key === null) {
            throw new InvalidConfigException('The "key" property must be set.');
        }

        if ($this->secret === null) {
            throw new InvalidConfigException('The "secret" property must be set.');
        }

        if ($this->bucket === null) {
            throw new InvalidConfigException('The "bucket" property must be set.');
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Yii::t('yuncms', 'Aws S3');
    }

    /**
     * @return \League\Flysystem\AwsS3v3\AwsS3Adapter
     */
    protected function prepareAdapter()
    {
        $config = [
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret
            ]
        ];

        if ($this->region !== null) {
            $config['region'] = $this->region;
        }

        if ($this->baseUrl !== null) {
            $config['base_url'] = $this->baseUrl;
        }

        $config['version'] = (($this->version !== null) ? $this->version : 'latest');

        $client = new S3Client($config);

        return new \League\Flysystem\AwsS3v3\AwsS3Adapter($client, $this->bucket, $this->prefix, $this->options);
    }
}