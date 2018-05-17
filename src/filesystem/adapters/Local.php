<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem\adapters;

use Yii;
use yii\base\InvalidConfigException;
use League\Flysystem\AdapterInterface;
use yuncms\filesystem\FilesystemAdapter;
use League\Flysystem\Adapter\Local as LocalAdapter;

/**
 * Class Local
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Local extends FilesystemAdapter
{
    /**
     * @var string
     */
    public $path = '@root/storage';

    /** @var string 如何对待软连接  */
    public $links = null;

    /**
     * @var array 权限
     */
    public $permissions = [];

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
        parent::init();
    }

    /**
     * 准备适配器
     * @return AdapterInterface
     */
    protected function createDriver()
    {
        $links = ($this->links ?? null) === 'skip'
            ? LocalAdapter::SKIP_LINKS
            : LocalAdapter::DISALLOW_LINKS;

        return new LocalAdapter($this->path, $links, $this->permissions);
    }
}
