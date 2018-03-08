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
use League\Flysystem\Adapter\Local;
use yuncms\helpers\FileHelper;

/**
 * Class Local
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class LocalAdapter extends Adapter
{
    /**
     * @var string
     */
    public $path;

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Yii::t('yuncms', 'Local Folder');
    }

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
     * @return Local
     */
    protected function prepareAdapter()
    {
        return new Local($this->path);
    }

    /**
     * @inheritdoc
     */
    public function getRootPath(): string
    {
        return FileHelper::normalizePath($this->path);
    }
}