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
use yii\base\InvalidConfigException;
use League\Flysystem\MountManager;

/**
 * Class Storage
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Filesystem extends Component
{
    public $default = 'local';

    /**
     * @var array filesystem parameters (name => value).
     */
    public $params = [];

    /**
     * @var MountManager
     */
    protected $mountManager;

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
     * @return object|null the filesystem of the specified ID. If `$throwException` is false and `$id`
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

    public function mountManager()
    {
        $filesystems = $this->getFilesystems();
        $this->mountManager = new MountManager();
    }

    /**
     * 获取磁盘
     * @param string $filesystem
     * @return object
     * @throws InvalidConfigException
     */
    public function filesystem($filesystem = null)
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
}