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
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemNotFoundException;

/**
 * Class Storage
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class FilesystemManager extends Component
{

    public $default = 'local';

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
     * Getter magic method.
     * This method is overridden to support accessing filesystems like reading properties.
     * @param string $name filesystem or property name
     * @return mixed the named property value
     * @throws InvalidConfigException
     * @throws \yii\base\UnknownPropertyException
     */
    public function __get($name)
    {
        if ($this->has($name)) {
            return $this->get($name);
        }

        return parent::__get($name);
    }

    /**
     * Checks if a property value is null.
     * This method overrides the parent implementation by checking if the named filesystem is loaded.
     * @param string $name the property name or the event name
     * @return bool whether the property value is null
     */
    public function __isset($name)
    {
        if ($this->has($name)) {
            return true;
        }

        return parent::__isset($name);
    }

    /**
     * Returns a value indicating whether the locator has the specified filesystem definition or has instantiated the filesystem.
     * This method may return different results depending on the value of `$checkInstance`.
     *
     * - If `$checkInstance` is false (default), the method will return a value indicating whether the locator has the specified
     *   filesystem definition.
     * - If `$checkInstance` is true, the method will return a value indicating whether the locator has
     *   instantiated the specified filesystem.
     *
     * @param string $id filesystem ID (e.g. `local`).
     * @param bool $checkInstance whether the method should check if the filesystem is shared and instantiated.
     * @return bool whether the locator has the specified filesystem definition or has instantiated the filesystem.
     * @see set()
     */
    public function has($id, $checkInstance = false)
    {
        return $checkInstance ? isset($this->_filesystems[$id]) : isset($this->_definitions[$id]);
    }

    /**
     * Returns the filesystem instance with the specified ID.
     *
     * @param string $id filesystem ID (e.g. `db`).
     * @param bool $throwException whether to throw an exception if `$id` is not registered with the locator before.
     * @return Adapter|object|null the filesystem of the specified ID. If `$throwException` is false and `$id`
     * is not registered before, null will be returned.
     * @throws InvalidConfigException if `$id` refers to a nonexistent filesystem ID
     * @see has()
     * @see set()
     */
    public function get($id, $throwException = true)
    {
        if (isset($this->_filesystems[$id])) {
            return $this->_filesystems[$id];
        }

        if (isset($this->_definitions[$id])) {
            $definition = $this->_definitions[$id];
            if (is_object($definition) && !$definition instanceof Closure) {
                return $this->_filesystems[$id] = $definition;
            }

            return $this->_filesystems[$id] = Yii::createObject($definition);
        } elseif ($throwException) {
            throw new InvalidConfigException("Unknown filesystem ID: $id");
        }

        return null;
    }

    /**
     * Registers a filesystem definition with this locator.
     *
     * For example,
     *
     * ```php
     * // a class name
     * $locator->set('cache', 'yii\caching\FileCache');
     *
     * // a configuration array
     * $locator->set('db', [
     *     'class' => 'yii\db\Connection',
     *     'dsn' => 'mysql:host=127.0.0.1;dbname=demo',
     *     'username' => 'root',
     *     'password' => '',
     *     'charset' => 'utf8',
     * ]);
     *
     * // an anonymous function
     * $locator->set('cache', function ($params) {
     *     return new \yii\caching\FileCache;
     * });
     *
     * // an instance
     * $locator->set('cache', new \yii\caching\FileCache);
     * ```
     *
     * If a filesystem definition with the same ID already exists, it will be overwritten.
     *
     * @param string $id filesystem ID (e.g. `db`).
     * @param mixed $definition the filesystem definition to be registered with this locator.
     * It can be one of the following:
     *
     * - a class name
     * - a configuration array: the array contains name-value pairs that will be used to
     *   initialize the property values of the newly created object when [[get()]] is called.
     *   The `class` element is required and stands for the the class of the object to be created.
     * - a PHP callable: either an anonymous function or an array representing a class method (e.g. `['Foo', 'bar']`).
     *   The callable will be called by [[get()]] to return an object associated with the specified filesystem ID.
     * - an object: When [[get()]] is called, this object will be returned.
     *
     * @throws InvalidConfigException if the definition is an invalid configuration array
     */
    public function set($id, $definition)
    {
        unset($this->_filesystems[$id]);

        if ($definition === null) {
            unset($this->_definitions[$id]);
            return;
        }

        if (is_object($definition) || is_callable($definition, true)) {
            // an object, a class name, or a PHP callable
            $this->_definitions[$id] = $definition;
        } elseif (is_array($definition)) {
            // a configuration array
            if (isset($definition['class'])) {
                $this->_definitions[$id] = $definition;
            } else {
                throw new InvalidConfigException("The configuration for the \"$id\" filesystem must contain a \"class\" element.");
            }
        } else {
            throw new InvalidConfigException("Unexpected configuration type for the \"$id\" filesystem: " . gettype($definition));
        }
    }

    /**
     * Removes the filesystem from the locator.
     * @param string $id the filesystem ID
     */
    public function clear($id)
    {
        unset($this->_definitions[$id], $this->_filesystems[$id]);
    }

    /**
     * 获取磁盘
     * @param string|null $filesystem
     * @return \League\Flysystem\Filesystem
     * @throws InvalidConfigException
     */
    public function disk($filesystem = null)
    {
        $filesystem = !is_null($filesystem) ? $filesystem : $this->default;
        return $this->get($filesystem);
    }

    /**
     * Returns the list of the filesystem definitions or the loaded filesystem instances.
     * @param bool $returnDefinitions whether to return filesystem definitions instead of the loaded filesystem instances.
     * @return array the list of the filesystem definitions or the loaded filesystem instances (ID => definition or instance).
     */
    public function getFilesystems($returnDefinitions = true)
    {
        return $returnDefinitions ? $this->_definitions : $this->_filesystems;
    }

    /**
     * Registers a set of filesystem definitions in this locator.
     *
     * This is the bulk version of [[set()]]. The parameter should be an array
     * whose keys are filesystem IDs and values the corresponding filesystem definitions.
     *
     * For more details on how to specify filesystem IDs and definitions, please refer to [[set()]].
     *
     * If a filesystem definition with the same ID already exists, it will be overwritten.
     *
     * The following is an example for registering two filesystem definitions:
     *
     * ```php
     * [
     *     'local' => [
     *         'class' => 'yuncms\filesystem\adapters\LocalAdapter',
     *         'path' => '@root/storage',
     *     ],
     * ]
     * ```
     *
     * @param array $filesystems filesystem definitions or instances
     * @throws InvalidConfigException
     */
    public function setFilesystems($filesystems)
    {
        foreach ($filesystems as $id => $filesystem) {
            $this->set($id, $filesystem);
        }
    }

    /**
     * Retrieve the prefix from an arguments array.
     *
     * @param array $arguments
     *
     * @throws InvalidArgumentException
     *
     * @return array [:prefix, :arguments]
     */
    public function filterPrefix(array $arguments)
    {
        if (empty($arguments)) {
            throw new InvalidArgumentException('At least one argument needed');
        }

        $path = array_shift($arguments);

        if (!is_string($path)) {
            throw new InvalidArgumentException('First argument should be a string');
        }

        list($prefix, $path) = $this->getPrefixAndPath($path);
        array_unshift($arguments, $path);

        return [$prefix, $arguments];
    }

    /**
     * 列出内容
     * @param string $directory
     * @param bool $recursive
     *
     * @throws InvalidArgumentException
     * @throws FilesystemNotFoundException
     *
     * @return array
     * @throws InvalidConfigException
     */
    public function listContents($directory = '', $recursive = false)
    {
        list($prefix, $directory) = $this->getPrefixAndPath($directory);
        $filesystem = $this->get($prefix);
        $result = $filesystem->listContents($directory, $recursive);
        foreach ($result as &$file) {
            $file['filesystem'] = $prefix;
        }
        return $result;
    }

    /**
     * 在两个磁盘之间复制文件
     * @param string $from
     * @param string $to
     * @param array $config
     *
     * @throws InvalidArgumentException
     * @throws FilesystemNotFoundException
     *
     * @return bool
     * @throws InvalidConfigException
     */
    public function copy($from, $to, array $config = [])
    {
        list($prefixFrom, $from) = $this->getPrefixAndPath($from);
        $buffer = $this->get($prefixFrom)->readStream($from);
        if ($buffer === false) {
            return false;
        }
        list($prefixTo, $to) = $this->getPrefixAndPath($to);
        $result = $this->get($prefixTo)->writeStream($to, $buffer, $config);
        if (is_resource($buffer)) {
            fclose($buffer);
        }
        return $result;
    }

    /**
     * 文件是否存在
     * @param string $path
     * @return bool
     * @throws InvalidConfigException
     */
    public function hasFile($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->has($path);
    }

    /**
     * 移动文件
     *
     * @param string $from
     * @param string $to
     * @param array $config
     *
     * @return bool
     * @throws InvalidConfigException
     */
    public function move($from, $to, array $config = [])
    {
        list($prefixFrom, $pathFrom) = $this->getPrefixAndPath($from);
        list($prefixTo, $pathTo) = $this->getPrefixAndPath($to);

        if ($prefixFrom === $prefixTo) {
            $filesystem = $this->get($prefixFrom);
            $renamed = $filesystem->rename($pathFrom, $pathTo);

            if ($renamed && isset($config['visibility'])) {
                return $filesystem->setVisibility($pathTo, $config['visibility']);
            }

            return $renamed;
        }

        $copied = $this->copy($from, $to, $config);

        if ($copied) {
            return $this->delete($from);
        }

        return false;
    }

    /**
     * 向文件追加内容
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool
     * @throws InvalidConfigException
     */
    public function put($path, $contents, $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->put($path, $contents, $config);
    }

    /**
     * 向文件追加流内容
     * @param string $path
     * @param resource $contents
     * @param array $config
     * @return bool
     * @throws InvalidConfigException
     */
    public function putStream($path, $contents, $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->putStream($path, $contents, $config);
    }

    /**
     * 读文件
     * @param string $path
     * @return bool|false|string
     * @throws InvalidConfigException
     */
    public function read($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->read($path);
    }

    /**
     * 读取流
     * @param string $path
     * @return bool|false|resource
     * @throws InvalidConfigException
     */
    public function readStream($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->readStream($path);
    }

    /**
     * 读取并删除文件
     * @param string $path
     * @return bool|false|string
     * @throws InvalidConfigException
     */
    public function readAndDelete($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->readAndDelete($path);
    }

    /**
     * 更新文件
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool
     * @throws InvalidConfigException
     */
    public function update($path, $contents, $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->update($path, $contents, $config);
    }

    /**
     * 更新流
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     * @throws InvalidConfigException
     */
    public function updateStream($path, $resource, $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->updateStream($path, $resource, $config);
    }

    /**
     * 重命名文件
     * @param string $path
     * @param string $newpath
     * @return bool
     * @throws InvalidConfigException
     */
    public function rename($path, $newpath)
    {
        return $this->move($path, $newpath);
    }

    /**
     * 删除目录
     * @param string $dirname
     * @return bool
     * @throws InvalidConfigException
     */
    public function deleteDir($dirname)
    {
        list($prefix, $path) = $this->getPrefixAndPath($dirname);
        return $this->get($prefix)->deleteDir($path);
    }

    /**
     * 写文件
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool
     * @throws InvalidConfigException
     */
    public function write($path, $contents, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->write($path, $contents, $config);
    }

    /**
     * 将流写入文件
     * @param string $path
     * @param resource $resource
     * @param array $config
     * @return bool
     * @throws InvalidConfigException
     */
    public function writeStream($path, $resource, array $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->writeStream($path, $resource, $config);
    }

    /**
     * 删除文件
     * @param string $path
     * @return bool
     * @throws InvalidConfigException
     */
    public function delete($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->delete($path);
    }

    /**
     * 创建目录
     * @param string $path
     * @param array $config
     * @return bool
     * @throws InvalidConfigException
     */
    public function createDir($path, $config = [])
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->createDir($path, $config);
    }

    /**
     * 列出指定路径文件
     * @param string $directory
     * @param bool $recursive
     * @return array
     * @throws InvalidConfigException
     */
    public function listFiles($directory = '', $recursive = false)
    {
        list($prefix, $path) = $this->getPrefixAndPath($directory);
        return $this->get($prefix)->listFiles($path, $recursive);
    }

    /**
     * 列出路径
     * @param string $directory
     * @param bool $recursive
     * @return array
     * @throws InvalidConfigException
     */
    public function listPaths($directory = '', $recursive = false)
    {
        list($prefix, $path) = $this->getPrefixAndPath($directory);
        return $this->get($prefix)->listPaths($path, $recursive);
    }

    /**
     * 获取 源数据
     * @param string $path
     * @param array $metadata
     * @return array
     * @throws InvalidConfigException
     */
    public function getWithMetadata($path, array $metadata)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->getWithMetadata($path, $metadata);
    }

    /**
     * 获取文件 Mimetype
     * @param string $path
     * @return bool|false|string
     * @throws InvalidConfigException
     */
    public function getMimetype($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->getMimetype($path);
    }

    /**
     * 获取文件创建的时间戳
     * @param string $path
     * @return bool|false|string
     * @throws InvalidConfigException
     */
    public function getTimestamp($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->getTimestamp($path);
    }

    /**
     * 获取文件可见性
     * @param string $path
     * @return bool|false|string
     * @throws InvalidConfigException
     */
    public function getVisibility($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->getVisibility($path);
    }

    /**
     * 获取文件大小
     * @param string $path
     * @return bool|false|int
     * @throws InvalidConfigException
     */
    public function getSize($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->getSize($path);
    }

    /**
     * 设置文件可见性
     * @param string $path
     * @param string $visibility
     * @return bool
     * @throws InvalidConfigException
     */
    public function setVisibility($path, $visibility)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->setVisibility($path, $visibility);
    }

    /**
     * 获取Metadata
     * @param string $path
     * @return array|false
     * @throws InvalidConfigException
     */
    public function getMetadata($path)
    {
        list($prefix, $path) = $this->getPrefixAndPath($path);
        return $this->get($prefix)->getMetadata($path);
    }

    /**
     * @param string $path
     *
     * @throws InvalidArgumentException
     *
     * @return string[] [:prefix, :path]
     */
    protected function getPrefixAndPath($path)
    {
        if (strpos($path, '://') < 1) {
            throw new InvalidArgumentException('No prefix detected in path: ' . $path);
        }

        return explode('://', $path, 2);
    }
}