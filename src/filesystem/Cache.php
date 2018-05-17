<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem;

use yii\caching\CacheInterface;
use League\Flysystem\Cached\Storage\AbstractCache;

/**
 * Class YiiCache
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Cache extends AbstractCache
{
    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * The cache key.
     *
     * @var string
     */
    protected $key;

    /**
     * The cache expiration time in minutes.
     *
     * @var integer
     */
    protected $expire;

    /**
     * Create a new cache instance.
     *
     * @param CacheInterface $yiiCache
     * @param string $key
     * @param integer $expire
     */
    public function __construct(CacheInterface $yiiCache, $key = 'flysystem', $expire = null)
    {
        $this->cache = $yiiCache;
        $this->key = $key;
        $this->expire = $expire;
    }

    /**
     * Load the cache.
     */
    public function load()
    {
        $contents = $this->cache->get($this->key);

        if ($contents !== false) {
            $this->setFromStorage($contents);
        }
    }

    /**
     * Persist the cache.
     */
    public function save()
    {
        $contents = $this->getForStorage();

        if (!is_null($this->expire)) {
            $this->cache->set($this->key, $contents, $this->expire);
        } else {
            $this->cache->set($this->key, $contents);
        }
    }
}
