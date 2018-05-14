<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem;

use Closure;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Cached\CachedAdapter;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yuncms\helpers\ArrayHelper;

/**
 * Class Storage
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 *
 * @property string $visibility
 * @property array $filesystems
 */
class FilesystemManager extends Component
{
    /**
     * Default Filesystem Disk
     *
     * @var string
     */
    public $default = 'local';

    /**
     * Default Cloud Filesystem Disk
     *
     * @var string
     */
    public $cloud = 'local';


    /**
     * @var array filesystem parameters (name => value).
     */
    public $params = [];

    /**
     * @var array shared disk instances indexed by their IDs
     */
    private $_filesystems = [];

    /**
     * @var array filesystem definitions indexed by their IDs
     */
    private $_definitions = [];

    /**
     * 获取磁盘
     * @param string|null $filesystem
     * @return FilesystemAdapter
     * @throws InvalidConfigException
     */
    public function disk($filesystem = null)
    {
        $filesystem = !is_null($filesystem) ? $filesystem : $this->default;
        return $this->get($filesystem);
    }

    /**
     * Get a default cloud filesystem instance.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function cloud()
    {
        $name = $this->getDefaultCloudDriver();
        return $this->disks[$name] = $this->get($name);
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return $this->default;
    }

    /**
     * Get the default cloud driver name.
     *
     * @return string
     */
    public function getDefaultCloudDriver()
    {
        return $this->cloud;
    }

    /**
     * Create a Flysystem instance with the given adapter.
     *
     * @param  \League\Flysystem\AdapterInterface $adapter
     * @param  array $config
     * @return \League\Flysystem\FilesystemInterface
     * @throws InvalidConfigException
     */
    protected function createFlysystem(AdapterInterface $adapter, array $config)
    {
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($this->cache, YiiCache::class);
            if (!$this->cache instanceof YiiCache) {
                throw new InvalidConfigException('The "cache" property must be an instance of \yii\caching\Cache subclasses.');
            }
            $adapter = new CachedAdapter($adapter, new Cache($this->cache, $this->cacheKey, $this->cacheDuration));
        }

        $cache = ArrayHelper::pull($config, 'cache');

        $config = ArrayHelper::only($config, ['visibility', 'disable_asserts', 'url']);

        if ($cache) {
            $adapter = new CachedAdapter($adapter, $this->createCacheStore($cache));
        }

        return new Flysystem($adapter, count($config) > 0 ? $config : null);
    }

    /**
     * Create a cache store instance.
     *
     * @param  mixed  $config
     * @return \League\Flysystem\Cached\CacheInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function createCacheStore($config)
    {
        if ($config === true) {
            return new MemoryStore;
        }

        return new Cache(
            $this->app['cache']->store($config['store']),
            $config['prefix'] ?? 'flysystem',
            $config['expire'] ?? null
        );
    }

    

}
