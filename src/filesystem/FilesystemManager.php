<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem;

use Closure;
use Yii;
use yii\base\Component;
use yii\caching\Cache as YiiCache;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yuncms\filesystem\contracts\Filesystem;
use League\Flysystem\AdapterInterface;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Filesystem as Flysystem;
use League\Flysystem\Adapter\Ftp as FtpAdapter;
use League\Flysystem\Rackspace\RackspaceAdapter;
use League\Flysystem\Adapter\Local as LocalAdapter;
use League\Flysystem\AwsS3v3\AwsS3Adapter as S3Adapter;
use League\Flysystem\Cached\Storage\Memory as MemoryStore;

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
     * @var string|YiiCache
     */
    public $cache = 'cache';

    /**
     * @var string
     */
    public $cacheKey = 'filesystem';

    /**
     * @var integer
     */
    public $cacheDuration = 0;

    /**
     * The array of resolved filesystem drivers.
     *
     * @var array
     */
    protected $disks = [];

    /**
     * The registered custom driver creators.
     *
     * @var array
     */
    protected $customCreators = [];

    /**
     * Attempt to get the disk from the local cache.
     *
     * @param  string $name
     * @return Filesystem
     */
    protected function get($name)
    {
        return $this->disks[$name] ?? $this->resolve($name);
    }

    /**
     * Resolve the given disk.
     *
     * @param  string $name
     * @return Filesystem
     *
     * @throws InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->disks[$name];
        $driverMethod = 'create' . ucfirst($config['adapter']) . 'Adapter';
        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        } else {
            throw new InvalidArgumentException("Adapter [{$config['adapter']}] is not supported.");
        }
    }

    /**
     * Adapt the filesystem implementation.
     *
     * @param  \League\Flysystem\FilesystemInterface  $filesystem
     * @return Filesystem
     */
    protected function adapt(FilesystemInterface $filesystem)
    {
        return new FilesystemAdapter($filesystem);
    }

    /**
     * Create a cache store instance.
     *
     * @param  mixed $config
     * @return \League\Flysystem\Cached\CacheInterface
     *
     * @throws \InvalidArgumentException
     * @throws InvalidConfigException
     */
    protected function createCacheStore($config)
    {
        if ($config === true) {
            return new MemoryStore;
        }
        if ($this->cache !== null) {
            $this->cache = Instance::ensure($config, YiiCache::class);
            if (!$this->cache instanceof YiiCache) {
                throw new InvalidConfigException('The "cache" property must be an instance of \yii\caching\Cache subclasses.');
            }
            return new Cache($this->cache, $this->cacheKey, $this->cacheDuration);
        }
        return new MemoryStore;
    }

    /**
     * Create a Flysystem instance with the given adapter.
     *
     * @param  \League\Flysystem\AdapterInterface $adapter
     * @param  array $config
     * @return \League\Flysystem\FilesystemInterface
     */
    protected function createFlysystem(AdapterInterface $adapter, array $config)
    {


        //$config = Arr::only($config, ['visibility', 'disable_asserts', 'url']);

        if (isset($config['cache'])) {
            $adapter = new CachedAdapter($adapter, $this->createCacheStore($config['cache']));
        }

        return new Flysystem($adapter, count($config) > 0 ? $config : null);
    }

    /**
     * Create an instance of the local driver.
     *
     * @param  array $config
     * @return FilesystemInterface
     */
    public function createLocalDriver(array $config)
    {
        $permissions = $config['permissions'] ?? [];

        $links = ($config['links'] ?? null) === 'skip'
            ? LocalAdapter::SKIP_LINKS
            : LocalAdapter::DISALLOW_LINKS;

        return $this->adapt($this->createFlysystem(new LocalAdapter(
            $config['root'], LOCK_EX, $links, $permissions
        ), $config));
    }

    /**
     * Create an instance of the ftp driver.
     *
     * @param  array $config
     * @return Filesystem
     */
    public function createFtpDriver(array $config)
    {
        return $this->adapt($this->createFlysystem(
            new FtpAdapter($config), $config
        ));
    }
}
