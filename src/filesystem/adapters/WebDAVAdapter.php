<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem\adapters;

use Sabre\DAV\Client;
use Yii;
use yii\base\InvalidConfigException;
use yuncms\filesystem\Adapter;

/**
 * Class WebDAVAdapter
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class WebDAVAdapter extends Adapter
{
    /**
     * @var string
     */
    public $baseUri;
    /**
     * @var string
     */
    public $userName;
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $proxy;
    /**
     * @var integer
     */
    public $authType;
    /**
     * @var integer
     */
    public $encoding;
    /**
     * @var string|null
     */
    public $prefix;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->baseUri === null) {
            throw new InvalidConfigException('The "baseUri" property must be set.');
        }

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Yii::t('yuncms', 'WebDAV');
    }

    /**
     * @return \League\Flysystem\WebDAV\WebDAVAdapter
     */
    protected function prepareAdapter()
    {
        $config = [];

        foreach ([
                     'baseUri',
                     'userName',
                     'password',
                     'proxy',
                     'authType',
                     'encoding',
                 ] as $name) {
            if ($this->$name !== null) {
                $config[$name] = $this->$name;
            }
        }

        return new \League\Flysystem\WebDAV\WebDAVAdapter(
            new Client($config),
            $this->prefix
        );
    }
}