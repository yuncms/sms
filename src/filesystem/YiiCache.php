<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\filesystem;

use yii\caching\Cache;
use League\Flysystem\Cached\Storage\AbstractCache;

/**
 * Class YiiCache
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class YiiCache extends AbstractCache
{
    /**
     * @var Cache
     */
    protected $yiiCache;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var integer
     */
    protected $duration;

    /**
     * @param Cache $yiiCache
     * @param string $key
     * @param integer $duration
     */
    public function __construct(Cache $yiiCache, $key = 'flysystem', $duration = 0)
    {
        $this->yiiCache = $yiiCache;
        $this->key = $key;
        $this->duration = $duration;
    }
    /**
     * @inheritdoc
     */
    public function load()
    {
        $contents = $this->yiiCache->get($this->key);
        if ($contents !== false) {
            $this->setFromStorage($contents);
        }
    }
    /**
     * @inheritdoc
     */
    public function save()
    {
        $this->yiiCache->set($this->key, $this->getForStorage(), $this->duration);
    }
}