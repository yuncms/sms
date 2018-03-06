<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\base;

use Closure;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * 服务层
 * @package yuncms\base
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Service extends Component
{
    /**
     * @var array shared service instances indexed by their IDs
     */
    private $_services = [];

    /**
     * @var array service definitions indexed by their IDs
     */
    private $_definitions = [];

    /**
     * Getter magic method.
     * This method is overridden to support accessing services like reading properties.
     * @param string $name service or property name
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
     * This method overrides the parent implementation by checking if the named service is loaded.
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
     * Returns a value indicating whether the locator has the specified service definition or has instantiated the service.
     * This method may return different results depending on the value of `$checkInstance`.
     *
     * - If `$checkInstance` is false (default), the method will return a value indicating whether the locator has the specified
     *   service definition.
     * - If `$checkInstance` is true, the method will return a value indicating whether the locator has
     *   instantiated the specified service.
     *
     * @param string $id service ID (e.g. `db`).
     * @param bool $checkInstance whether the method should check if the service is shared and instantiated.
     * @return bool whether the locator has the specified service definition or has instantiated the service.
     * @see set()
     */
    public function has($id, $checkInstance = false)
    {
        return $checkInstance ? isset($this->_services[$id]) : isset($this->_definitions[$id]);
    }

    /**
     * Returns the service instance with the specified ID.
     *
     * @param string $id service ID (e.g. `db`).
     * @param bool $throwException whether to throw an exception if `$id` is not registered with the locator before.
     * @return object|null the service of the specified ID. If `$throwException` is false and `$id`
     * is not registered before, null will be returned.
     * @throws InvalidConfigException if `$id` refers to a nonexistent service ID
     * @see has()
     * @see set()
     */
    public function get($id, $throwException = true)
    {
        if (isset($this->_services[$id])) {
            return $this->_services[$id];
        }

        if (isset($this->_definitions[$id])) {
            $definition = $this->_definitions[$id];
            if (is_object($definition) && !$definition instanceof Closure) {
                return $this->_services[$id] = $definition;
            }

            return $this->_services[$id] = Yii::createObject($definition);
        } elseif ($throwException) {
            throw new InvalidConfigException("Unknown service ID: $id");
        }

        return null;
    }

    /**
     * Registers a service definition with this locator.
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
     * If a service definition with the same ID already exists, it will be overwritten.
     *
     * @param string $id service ID (e.g. `db`).
     * @param mixed $definition the service definition to be registered with this locator.
     * It can be one of the following:
     *
     * - a class name
     * - a configuration array: the array contains name-value pairs that will be used to
     *   initialize the property values of the newly created object when [[get()]] is called.
     *   The `class` element is required and stands for the the class of the object to be created.
     * - a PHP callable: either an anonymous function or an array representing a class method (e.g. `['Foo', 'bar']`).
     *   The callable will be called by [[get()]] to return an object associated with the specified service ID.
     * - an object: When [[get()]] is called, this object will be returned.
     *
     * @throws InvalidConfigException if the definition is an invalid configuration array
     */
    public function set($id, $definition)
    {
        unset($this->_services[$id]);

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
                throw new InvalidConfigException("The configuration for the \"$id\" service must contain a \"class\" element.");
            }
        } else {
            throw new InvalidConfigException("Unexpected configuration type for the \"$id\" service: " . gettype($definition));
        }
    }

    /**
     * Removes the service from the locator.
     * @param string $id the service ID
     */
    public function clear($id)
    {
        unset($this->_definitions[$id], $this->_services[$id]);
    }

    /**
     * Returns the list of the service definitions or the loaded service instances.
     * @param bool $returnDefinitions whether to return service definitions instead of the loaded service instances.
     * @return array the list of the service definitions or the loaded service instances (ID => definition or instance).
     */
    public function getServices($returnDefinitions = true)
    {
        return $returnDefinitions ? $this->_definitions : $this->_services;
    }

    /**
     * Registers a set of service definitions in this locator.
     *
     * This is the bulk version of [[set()]]. The parameter should be an array
     * whose keys are service IDs and values the corresponding service definitions.
     *
     * For more details on how to specify service IDs and definitions, please refer to [[set()]].
     *
     * If a service definition with the same ID already exists, it will be overwritten.
     *
     * The following is an example for registering two service definitions:
     *
     * ```php
     * [
     *     'db' => [
     *         'class' => 'yii\db\Connection',
     *         'dsn' => 'sqlite:path/to/file.db',
     *     ],
     *     'cache' => [
     *         'class' => 'yii\caching\DbCache',
     *         'db' => 'db',
     *     ],
     * ]
     * ```
     *
     * @param array $services service definitions or instances
     * @throws InvalidConfigException
     */
    public function setServices($services)
    {
        foreach ($services as $id => $service) {
            $this->set($id, $service);
        }
    }
}