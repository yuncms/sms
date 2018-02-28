<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\helpers;

use yii\base\InvalidArgumentException;

/**
 * Class Json
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Json extends \yii\helpers\Json
{
    /**
     * Decodes the given JSON string into a PHP data structure, only if the string is valid JSON.
     *
     * @param string $str The string to be decoded, if it's valid JSON.
     * @param bool $asArray Whether to return objects in terms of associative arrays.
     * @return mixed The PHP data, or the given string if it wasn’t valid JSON.
     */
    public static function decodeIfJson(string $str, bool $asArray = true)
    {
        try {
            return static::decode($str, $asArray);
        } catch (InvalidArgumentException $e) {
            // Wasn't JSON
            return $str;
        }
    }
}