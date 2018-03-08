<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem;

use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\caching\Cache;
use yii\di\Instance;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Cached\CachedAdapter;

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
     * @var string|null The adapter URL
     */
    public $url;

    /**
     * @var bool|null Whether the volume has a public URL
     */
    public $hasUrls;

    /**
     * 初始化适配器
     * @throws InvalidConfigException
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
     * Returns the URL to the source, if it’s accessible via HTTP traffic.
     *
     * @return string|false The root URL, or `false` if there isn’t one
     */
    public function getRootUrl()
    {
        return false;
    }

    /**
     * 获取文件的Url访问路径
     * @param string $path
     * @return string
     * @throws NotSupportedException
     */
    public function getUrl($path)
    {
        if (is_null($this->url)) {
            throw new NotSupportedException('"getUrl" is not implemented.');
        } else {
            return $this->url . '/' . $path;
        }
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