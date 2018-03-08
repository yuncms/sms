<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper
{
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