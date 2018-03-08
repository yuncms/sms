<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\base;

use Countable;
use ArrayAccess;
use ArrayIterator;
use JsonSerializable;
use IteratorAggregate;
use Serializable;
use Traversable;
use yuncms\helpers\ArrayHelper;
use yuncms\helpers\Json;

/**
 * Collection
 *
 * @package yuncms\base
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Collection implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable, Serializable
{
    /**
     * The collection data.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @param  mixed $items
     * @return Collection
     */
    public static function make($items = []): Collection
    {
        return new static($items);
    }

    /**
     * set data.
     *
     * @param mixed $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Return all items.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Return specific items.
     *
     * @param array $keys
     * @return array
     */
    public function only(array $keys): array
    {
        $return = [];
        foreach ($keys as $key) {
            $value = $this->get($key);
            if (!is_null($value)) {
                $return[$key] = $value;
            }
        }
        return $return;
    }

    /**
     * Get all items except for those with the specified keys.
     *
     * @param mixed $keys
     * @return Collection
     */
    public function except($keys): Collection
    {
        $keys = is_array($keys) ? $keys : func_get_args();
        return new static(ArrayHelper::except($this->items, $keys));
    }

    /**
     * Merge data.
     *
     * @param Collection|array $items
     *
     * @return array
     */
    public function merge($items): array
    {
        foreach ($items as $key => $value) {
            $this->set($key, $value);
        }
        return $this->all();
    }

    /**
     * To determine Whether the specified element exists.
     *
     * @param string $key
     * @return bool
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Retrieve the first item.
     *
     * @return mixed
     */
    public function first()
    {
        return reset($this->items);
    }

    /**
     * Retrieve the last item.
     *
     * @return mixed
     */
    public function last()
    {
        $end = end($this->items);
        reset($this->items);
        return $end;
    }

    /**
     * add the item value.
     *
     * @param string $key
     * @param mixed $value
     */
    public function add($key, $value)
    {
        ArrayHelper::set($this->items, $key, $value);
    }

    /**
     * Set the item value.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        ArrayHelper::set($this->items, $key, $value);
    }

    /**
     * Retrieve item from Collection.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return ArrayHelper::get($this->items, $key, $default);
    }

    /**
     * Remove item form Collection.
     *
     * @param string $key
     */
    public function forget($key)
    {
        ArrayHelper::forget($this->items, $key);
    }

    /**
     * Build to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->all();
    }

    /**
     * Build to json.
     *
     * @param int $option
     * @return string
     */
    public function toJson($option = JSON_UNESCAPED_UNICODE): string
    {
        return Json::encode($this->all(), $option);
    }

    /**
     * To string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource
     */
    public function jsonSerialize()
    {
        return $this->items;
    }

    /**
     * String representation of object.
     *
     * @return string the string representation of the object or null
     */
    public function serialize(): string
    {
        return serialize($this->items);
    }

    /**
     * Retrieve an external iterator.
     *
     * @return ArrayIterator|Traversable An instance of an object implementing Iterator or Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    /**
     * Count elements of an object.
     *
     * @return int The custom count as an integer.
     *             The return value is cast to an integer
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Constructs the object.
     *
     * @param string $serialized The string representation of the object.
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->items = unserialize($serialized);
    }

    /**
     * Get a data by key.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Assigns a value to the specified data.
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Whether or not an data exists by key.
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key): bool
    {
        return $this->has($key);
    }

    /**
     * Unsets an data by key.
     *
     * @param string $key
     */
    public function __unset($key)
    {
        $this->forget($key);
    }

    /**
     * var_export.
     *
     * @return array
     */
    public function __set_state(): array
    {
        return $this->all();
    }

    /**
     * Whether a offset exists.
     *
     * @param mixed $offset An offset to check for.
     * @return bool true on success or false on failure.
     *              The return value will be casted to boolean if non-boolean was returned
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * Offset to unset.
     *
     * @param mixed $offset The offset to unset.
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            $this->forget($offset);
        }
    }

    /**
     * Offset to retrieve.
     *
     * @param mixed $offset The offset to retrieve.
     * @return mixed Can return all value types
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->get($offset) : null;
    }

    /**
     * Offset to set.
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }
}