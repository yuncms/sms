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
use Dropbox\Client;

/**
 * Class Dropbox
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class DropboxFilesystemAdapter extends FilesystemAdapter
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
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Yii::t('yuncms', 'Dropbox');
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
