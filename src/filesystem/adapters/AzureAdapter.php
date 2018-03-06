<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem\adapters;

use WindowsAzure\Common\ServicesBuilder;
use yii\base\InvalidConfigException;
use yuncms\filesystem\Adapter;

/**
 * Class AzureAdapter
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class AzureAdapter extends Adapter
{
    /**
     * @var string
     */
    public $accountName;
    /**
     * @var string
     */
    public $accountKey;
    /**
     * @var string
     */
    public $container;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->accountName === null) {
            throw new InvalidConfigException('The "accountName" property must be set.');
        }

        if ($this->accountKey === null) {
            throw new InvalidConfigException('The "accountKey" property must be set.');
        }

        if ($this->container === null) {
            throw new InvalidConfigException('The "container" property must be set.');
        }

        parent::init();
    }

    /**
     * @return \League\Flysystem\Azure\AzureAdapter
     */
    protected function prepareAdapter()
    {
        return new \League\Flysystem\Azure\AzureAdapter(
            ServicesBuilder::getInstance()->createBlobService(sprintf(
                'DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s',
                base64_encode($this->accountName),
                base64_encode($this->accountKey)
            )),
            $this->container
        );
    }
}