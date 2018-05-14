<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem\adapters;


use Yii;
use yii\base\InvalidConfigException;
use League\Flysystem\Adapter\Local;
use League\Flysystem\AdapterInterface;
use yuncms\filesystem\FilesystemAdapter;
use yuncms\helpers\FileHelper;

/**
 * Class Local
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class LocalFilesystemAdapter extends FilesystemAdapter
{
    /**
     * @var string
     */
    public $path = '@root/storage';

    /**
     * @inheritdoc
     * @throws \yii\base\Exception
     */
    public function init()
    {
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" property must be set.');
        }
        $this->path = Yii::getAlias($this->path);
        if (!is_dir($this->path)) {
            FileHelper::createDirectory($this->path);
        }
        parent::init();
    }

    /**
     * 准备适配器
     * @return AdapterInterface
     */
    protected function createDriver()
    {
        return new Local($this->path);
    }
}
