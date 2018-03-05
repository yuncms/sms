<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */


namespace yuncms\filesystem;


use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\base\Component;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Cached\CachedAdapter;
use yii\di\Instance;

/**
 * Class Filesystem
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
abstract class Adapter extends Component
{
    /**
     * @var \League\Flysystem\FilesystemInterface
     */
    protected $adapter;

    /**
     * @var string|null
     */
    public $cache;

    /**
     * @var string
     */
    public $cacheKey = 'flysystem';

    /**
     * @var integer
     */
    public $cacheDuration = 3600;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $adapter = $this->prepareAdapter();
        if ($this->cache !== null) {
            /* @var Cache $cache */
            $cache = Instance::ensure($this->cache, Cache::class);
            if (!$cache instanceof Cache) {
                throw new InvalidConfigException('The "cache" property must be an instance of \yii\caching\Cache subclasses.');
            }
            $adapter = new CachedAdapter($adapter, new YiiCache($cache, $this->cacheKey, $this->cacheDuration));
        }
        // And use that to create the file system
        $this->adapter = new \League\Flysystem\Filesystem($adapter);
    }

    /**
     * 准备适配器
     * @return AdapterInterface
     */
    abstract protected function prepareAdapter();

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->adapter, $method], $parameters);
    }
}