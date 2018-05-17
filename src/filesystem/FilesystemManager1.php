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
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;

/**
 * Class Storage
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 *
 * @property string $visibility
 * @property array $filesystems
 */
class FilesystemManager1 extends Component
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
     * 磁盘配置
     * @var array
     */
    public $disks = [];

    private $_disks = [];


    /**
     * 获取磁盘
     * @param string|null $filesystem
     * @return Filesystem
     */
    public function cloud($filesystem = null)
    {
        $filesystem = !is_null($filesystem) ? $filesystem : $this->cloud;
        return $this->get($filesystem);
    }

    /**
     * 获取磁盘
     * @param string|null $filesystem
     * @return Filesystem
     */
    public function disk($filesystem = null)
    {
        $filesystem = !is_null($filesystem) ? $filesystem : $this->default;
        return $this->get($filesystem);
    }

    /**
     * Attempt to get the disk from the local cache.
     *
     * @param  string $name
     * @return \yuncms\filesystem\Filesystem
     */
    protected function get($name)
    {
        return $this->_disks[$name] ?? $this->resolve($name);
    }

    /**
     * Resolve the given disk.
     *
     * @param  string $name
     * @return \yuncms\filesystem\Filesystem
     * @throws \InvalidArgumentException
     */
    protected function resolve($name)
    {
        $config = $this->disks[$name];

//        if (isset($this->customCreators[$config['driver']])) {
//            return $this->callCustomCreator($config);
//        }

        $driverMethod = 'create' . ucfirst($config['adapter']) . 'Driver';
        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        } else {
            throw new InvalidArgumentException("Adapter [{$config['adapter']}] is not supported.");
        }
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultAdapter()
    {
        return $this->default;
    }

    /**
     * Get the default cloud driver name.
     *
     * @return string
     */
    public function getDefaultCloudAdapter()
    {
        return $this->cloud;
    }

    /**
     * Dynamically call the default driver instance.
     *
     * @param  string $method
     * @param  array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return $this->disk()->$method(...$parameters);
    }
}
