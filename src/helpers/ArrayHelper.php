<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\helpers;

use ArrayAccess;
use Closure;

/**
 * Class ArrayHelper
 * @package yuncms\helpers
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ArrayHelper extends \yii\helpers\ArrayHelper
{

    /**
     * Add an element to an array using "dot" notation if it doesn't exist.
     *
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public static function add($array, $key, $value): array
    {
        if (is_null(static::get($array, $key))) {
            static::set($array, $key, $value);
        }
        return $array;
    }

    /**
     * Build a new array using a callback.
     *
     * @param array $array
     * @param Closure $callback
     * @return array
     */
    public static function build($array, Closure $callback): array
    {
        $results = [];
        foreach ($array as $key => $value) {
            list($innerKey, $innerValue) = call_user_func($callback, $key, $value);
            $results[$innerKey] = $innerValue;
        }
        return $results;
    }

    /**
     * Divide an array into two arrays. One with keys and the other with values.
     *
     * @param array $array
     * @return array
     */
    public static function divide($array): array
    {
        return [
            array_keys($array),
            array_values($array),
        ];
    }

    /**
     * Return the first element in an array passing a given truth test.
     *
     * @param array $array
     * @param \Closure $callback
     * @param mixed $default
     * @return mixed
     */
    public static function first($array, $callback, $default = null)
    {
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $key, $value)) {
                return $value;
            }
        }
        return $default;
    }

    /**
     * Return the last element in an array passing a given truth test.
     *
     * @param array $array
     * @param Closure $callback
     * @param mixed $default
     * @return mixed
     */
    public static function last($array, $callback, $default = null)
    {
        return static::first(array_reverse($array), $callback, $default);
    }

    /**
     * Flatten a multi-dimensional array into a single level.
     *
     * @param array $array
     * @return array
     */
    public static function flatten($array): array
    {
        $return = [];
        array_walk_recursive($array, function ($x) use (&$return) {
            $return[] = $x;
        });
        return $return;
    }

    /**
     * Get all of the given array except for a specified array of items.
     *
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    public static function except($array, $keys): array
    {
        return array_diff_key($array, array_flip((array)$keys));
    }

    /**
     * Fetch a flattened array of a nested array element.
     *
     * @param array $array
     * @param string $key
     * @return array
     */
    public static function fetch($array, $key): array
    {
        $results = [];
        foreach (explode('.', $key) as $segment) {
            $results = [];
            foreach ($array as $value) {
                $value = (array)$value;
                $results[] = $value[$segment];
            }
            $array = array_values($results);
        }
        return array_values($results);
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (is_null($key)) {
            return $array;
        }
        if (isset($array[$key])) {
            return $array[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }
            $array = $array[$segment];
        }
        return $array;
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    public static function only($array, $keys): array
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }

    /**
     * Set an array item to a given value using "dot" notation.
     *
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public static function set(&$array, $key, $value): array
    {
        if (is_null($key)) {
            return $array = $value;
        }
        $keys = explode('.', $key);
        while (count($keys) > 1) {
            $key = array_shift($keys);
            // If the key doesn't exist at this depth, we will just create an empty array
            // to hold the next value, allowing us to create the arrays to hold final
            // values at the correct depth. Then we'll keep digging into the array.
            if (!isset($array[$key]) || !is_array($array[$key])) {
                $array[$key] = [];
            }
            $array = &$array[$key];
        }
        $array[array_shift($keys)] = $value;
        return $array;
    }

    /**
     * Pluck an array of values from an array.
     *
     * @param array $array
     * @param string $value
     * @param string $key
     * @return array
     */
    public static function pluck($array, $value, $key = null): array
    {
        $results = [];
        foreach ($array as $item) {
            $itemValue = is_object($item) ? $item->{$value} : $item[$value];
            // If the key is "null", we will just append the value to the array and keep
            // looping. Otherwise we will key the array using the value of the key we
            // received from the developer. Then we'll return the final array form.
            if (is_null($key)) {
                $results[] = $itemValue;
            } else {
                $itemKey = is_object($item) ? $item->{$key} : $item[$key];
                $results[$itemKey] = $itemValue;
            }
        }
        return $results;
    }

    /**
     * Get a value from the array, and remove it.
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function pull(&$array, $key, $default = null)
    {
        $value = static::get($array, $key, $default);
        static::forget($array, $key);
        return $value;
    }

    /**
     * Remove one or many array items from a given array using "dot" notation.
     *
     * @param array $array
     * @param array|string $keys
     */
    public static function forget(&$array, $keys)
    {
        $original = &$array;
        foreach ((array)$keys as $key) {
            $parts = explode('.', $key);
            while (count($parts) > 1) {
                $part = array_shift($parts);
                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                }
            }
            unset($array[array_shift($parts)]);
            // clean up after each pass
            $array = &$original;
        }
    }

    /**
     * Sort the array using the given Closure.
     *
     * @param array $array
     * @param Closure $callback
     *
     * @return array
     */
    public static function sort($array, Closure $callback):array
    {
        $results = [];
        foreach ($array as $key => $value) {
            $results[$key] = $callback($value);
        }
        return $results;
    }

    /**
     * Filter the array using the given Closure.
     *
     * @param array $array
     * @param Closure $callback
     * @return array
     */
    public static function where($array, Closure $callback):array
    {
        $filtered = [];
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $key, $value)) {
                $filtered[$key] = $value;
            }
        }
        return $filtered;
    }

    /**
     * Prepends or appends a value to an array.
     *
     * @param array &$array the array to be prepended/appended to
     * @param mixed $value the value to prepend/append to the array
     * @param bool $prepend `true` will prepend the value; `false` will append it
     */
    public static function prependOrAppend(array &$array, $value, bool $prepend)
    {
        if ($prepend) {
            array_unshift($array, $value);
        } else {
            $array[] = $value;
        }
    }

    /**
     * Filters an array to only the values where a given key (the name of a
     * sub-array key or sub-object property) is set to a given value.
     *
     * Array keys are preserved.
     *
     * @param array|\Traversable $array the array that needs to be indexed or grouped
     * @param string|\Closure $key the column name or anonymous function which result will be used to index the array
     * @param mixed $value the value that $key should be compared with
     * @param bool $strict whether a strict type comparison should be used when checking array element values against $value
     *
     * @return array the filtered array
     */
    public static function filterByValue($array, $key, $value, bool $strict = false): array
    {
        $result = [];

        foreach ($array as $i => $element) {
            $elementValue = static::getValue($element, $key);
            /** @noinspection TypeUnsafeComparisonInspection */
            if (($strict && $elementValue === $value) || (!$strict && $elementValue == $value)) {
                $result[$i] = $element;
            }
        }

        return $result;
    }

    /**
     * Filters empty strings from an array.
     *
     * @param array $arr
     * @return array
     */
    public static function filterEmptyStringsFromArray(array $arr): array
    {
        return array_filter($arr, function ($value): bool {
            return $value !== '';
        });
    }

    /**
     * Returns the first key in a given array.
     *
     * @param array $arr
     *
     * @return string|int|null The first key, whether that is a number (if the array is numerically indexed) or a string, or null if $arr isn’t an array, or is empty.
     */
    public static function firstKey(array $arr)
    {
        /** @noinspection LoopWhichDoesNotLoopInspection */
        foreach ($arr as $key => $value) {
            return $key;
        }

        return null;
    }

    /**
     * Returns the first value in a given array.
     *
     * @param array $arr
     *
     * @return mixed The first value, or null if $arr isn’t an array, or is empty.
     */
    public static function firstValue(array $arr)
    {
        return !empty($arr) ? reset($arr) : null;
    }

    /**
     * Renames an item in an array. If the new key already exists in the array and the old key doesn’t,
     * the array will be left unchanged.
     *
     * @param array $array the array to extract value from
     * @param string $oldKey old key name of the array element
     * @param string $newKey new key name of the array element
     * @param mixed $default the default value to be set if the specified old key does not exist
     *
     * @return void
     */
    public static function rename(array &$array, string $oldKey, string $newKey, $default = null)
    {
        if (!array_key_exists($newKey, $array) || array_key_exists($oldKey, $array)) {
            $array[$newKey] = static::remove($array, $oldKey, $default);
        }
    }
}