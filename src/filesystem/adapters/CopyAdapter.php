<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\filesystem\adapters;

use yii\base\InvalidConfigException;
use yuncms\filesystem\Adapter;
use Barracuda\Copy\API;

/**
 * Class Copy
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class CopyAdapter extends Adapter
{
    /**
     * @var string
     */
    public $consumerKey;
    /**
     * @var string
     */
    public $consumerSecret;
    /**
     * @var string
     */
    public $accessToken;
    /**
     * @var string
     */
    public $tokenSecret;
    /**
     * @var string|null
     */
    public $prefix;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->consumerKey === null) {
            throw new InvalidConfigException('The "consumerKey" property must be set.');
        }

        if ($this->consumerSecret === null) {
            throw new InvalidConfigException('The "consumerSecret" property must be set.');
        }

        if ($this->accessToken === null) {
            throw new InvalidConfigException('The "accessToken" property must be set.');
        }

        if ($this->tokenSecret === null) {
            throw new InvalidConfigException('The "tokenSecret" property must be set.');
        }

        parent::init();
    }

    /**
     * @return \League\Flysystem\Copy\CopyAdapter
     */
    protected function prepareAdapter()
    {
        return new \League\Flysystem\Copy\CopyAdapter(
            new API($this->consumerKey, $this->consumerSecret, $this->accessToken, $this->tokenSecret),
            $this->prefix
        );
    }
}