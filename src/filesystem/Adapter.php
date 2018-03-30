<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\di\Instance;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Cached\CachedAdapter;

/**
 * Class Filesystem
 *
 * @method getAdapter()
 * @method  has($path)
 * @method write($path, $contents, array $config = [])
 * @method writeStream($path, $resource, array $config = [])
 * @method put($path, $contents, array $config = [])
 * @method putStream($path, $resource, array $config = [])
 * @method readAndDelete($path)
 * @method update($path, $contents, array $config = [])
 * @method updateStream($path, $resource, array $config = [])
 * @method read($path)
 * @method readStream($path)
 * @method rename($path, $newpath)
 * @method copy($path, $newpath)
 * @method delete($path)
 * @method deleteDir($dirname)
 * @method createDir($dirname, array $config = [])
 * @method listContents($directory = '', $recursive = false)
 * @method getMimetype($path)
 * @method getTimestamp($path)
 * @method getVisibility($path)
 * @method getSize($path)
 * @method setVisibility($path, $visibility)
 * @method getMetadata($path)
 * @method get($path, Handler $handler = null)
 * @method assertPresent($path)
 * @method assertAbsent($path)
 *
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
     * @var string|null The adapter URL
     */
    public $url;

    /**
     * 初始化适配器
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
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
     * @inheritdoc
     */
    abstract public static function displayName();

    /**
     * 准备适配器
     * @return AdapterInterface
     */
    abstract protected function prepareAdapter();

    /**
     * 获取Web访问Url
     * @param string $file
     * @return string
     * @throws InvalidConfigException
     */
    public function getUrl($file)
    {
        if ($this->url !== null) {
            return $this->getRootUrl() . $file;
        } else {
            throw new InvalidConfigException('The "url" property must be set.');
        }
    }

    /**
     * Returns the URL to the source, if it’s accessible via HTTP traffic.
     *
     * @return string|false The root URL, or `false` if there isn’t one
     */
    public function getRootUrl()
    {
        if ($this->url !== null) {
            return rtrim(Yii::getAlias($this->url), '/') . '/';
        }
        return false;
    }

    /**
     * 魔术方法，执行适配器方法
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->adapter, $method], $parameters);
    }
}