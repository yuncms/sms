<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem\adapters;

use yii\base\InvalidConfigException;
use yuncms\filesystem\Adapter;
use Dropbox\Client;

/**
 * Class Dropbox
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class DropboxAdapter extends Adapter
{
    /**
     * @var string
     */
    public $token;
    /**
     * @var string
     */
    public $app;
    /**
     * @var string|null
     */
    public $prefix;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->token === null) {
            throw new InvalidConfigException('The "token" property must be set.');
        }

        if ($this->app === null) {
            throw new InvalidConfigException('The "app" property must be set.');
        }

        parent::init();
    }

    /**
     * @return \League\Flysystem\Dropbox\DropboxAdapter
     */
    protected function prepareAdapter()
    {
        return new \League\Flysystem\Dropbox\DropboxAdapter(
            new Client($this->token, $this->app),
            $this->prefix
        );
    }
}