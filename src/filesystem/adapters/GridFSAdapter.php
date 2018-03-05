<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\filesystem\adapters;

use MongoClient;
use yii\base\InvalidConfigException;
use yuncms\filesystem\Adapter;

/**
 * Class GridFSAdapter
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class GridFSAdapter extends Adapter
{
    /**
     * @var string
     */
    public $server;
    /**
     * @var string
     */
    public $database;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->server === null) {
            throw new InvalidConfigException('The "server" property must be set.');
        }

        if ($this->database === null) {
            throw new InvalidConfigException('The "database" property must be set.');
        }

        parent::init();
    }

    /**
     * @return \League\Flysystem\GridFS\GridFSAdapter
     */
    protected function prepareAdapter()
    {
        return new \League\Flysystem\GridFS\GridFSAdapter((new MongoClient($this->server))->selectDB($this->database)->getGridFS());
    }
}