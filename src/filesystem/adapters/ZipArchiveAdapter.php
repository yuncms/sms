<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem\adapters;

use Yii;
use yii\base\InvalidConfigException;
use yuncms\filesystem\Adapter;

/**
 * Class ZipArchiveAdapter
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class ZipArchiveAdapter extends Adapter
{
    /**
     * @var string
     */
    public $path;
    /**
     * @var string|null
     */
    public $prefix;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" property must be set.');
        }

        $this->path = Yii::getAlias($this->path);

        parent::init();
    }

    /**
     * @return \League\Flysystem\ZipArchive\ZipArchiveAdapter
     */
    protected function prepareAdapter()
    {
        return new \League\Flysystem\ZipArchive\ZipArchiveAdapter(
            $this->path,
            null,
            $this->prefix
        );
    }
}