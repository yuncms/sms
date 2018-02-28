<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\helpers;

use Yii;
use Stringy\Stringy as BaseStringy;

/**
 * Class StringHelper
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class StringHelper extends \yii\helpers\StringHelper
{
    /**
     * @var
     */
    private static $_asciiCharMap;

    /**
     * Returns ASCII character mappings, merging in any custom defined mappings from the 'customAsciiCharMappings'
     * config setting.
     *
     * @return array The fully merged ASCII character mappings.
     */
    public static function asciiCharMap(): array
    {
        if (self::$_asciiCharMap !== null) {
            return self::$_asciiCharMap;
        }

        // Get the map from Stringy.
        self::$_asciiCharMap = (new Stringy(''))->getAsciiCharMap();

        return self::$_asciiCharMap;
    }

    /**
     * 查找 指定字符串出现的位置
     * @param string $string
     * @param string $needle
     * @param int $offset
     * @return bool|false|int
     */
    public static function byteStrPos($string, $needle, $offset = 0)
    {
        return mb_strpos($string, $needle, $offset, '8bit');
    }

    /**
     * 提取两个字符串之间的值，不包括分隔符
     *
     * @param string $string 待提取的只付出
     * @param string $start 开始字符串
     * @param string|null $end 结束字符串，省略将返回所有的。
     * @return bool|string substring between $start and $end or false if either string is not found
     */
    public static function byteStrBetween($string, $start, $end = null)
    {
        if (($startPos = static::byteStrPos($string, $start)) !== false) {
            if ($end) {
                if (($end_pos = static::byteStrPos($string, $end, $startPos + static::byteLength($start))) !== false) {
                    return static::byteSubstr($string, $startPos + static::byteLength($start), $end_pos - ($startPos + static::byteLength($start)));
                }
            } else {
                return static::byteSubstr($string, $startPos);
            }
        }
        return false;
    }

    /**
     * Generates a valid v4 UUID string. See [http://stackoverflow.com/a/2040279/684]
     *
     * @return string The UUID.
     * @throws \Exception
     */
    public static function UUID(): string
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            random_int(0, 0xffff), random_int(0, 0xffff),

            // 16 bits for "time_mid"
            random_int(0, 0xffff),

            // 16 bits for "time_hi_and_version", four most significant bits holds version number 4
            random_int(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res", 8 bits for "clk_seq_low", two most significant bits holds zero and
            // one for variant DCE1.1
            random_int(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            random_int(0, 0xffff), random_int(0, 0xffff), random_int(0, 0xffff)
        );
    }

    /**
     * Returns is the given string matches a v4 UUID pattern.
     *
     * @param string $uuid The string to check.
     * @return bool Whether the string matches a v4 UUID pattern.
     */
    public static function isUUID(string $uuid): bool
    {
        return !empty($uuid) && preg_match('/[A-Z0-9]{8}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{12}/ui', $uuid);
    }

    /**
     * Converts all characters in the string to lowercase. An alias for PHP's mb_strtolower().
     *
     * @param string $str The string to convert to lowercase.
     * @return string The lowercase string.
     */
    public static function toLowerCase(string $str): string
    {
        return (string)BaseStringy::create($str)->toLowerCase();
    }

    /**
     * Converts an object to its string representation. If the object is an array, will glue the array elements togeter
     * with the $glue param. Otherwise will cast the object to a string.
     *
     * @param mixed $object The object to convert to a string.
     * @param string $glue The glue to use if the object is an array.
     * @return string The string representation of the object.
     */
    public static function toString($object, string $glue = ','): string
    {
        if (is_scalar($object) || (is_object($object) && method_exists($object, '__toString'))) {
            return (string)$object;
        }
        if (is_array($object) || $object instanceof \IteratorAggregate) {
            $stringValues = [];
            foreach ($object as $value) {
                if (($value = static::toString($value, $glue)) !== '') {
                    $stringValues[] = $value;
                }
            }
            return implode($glue, $stringValues);
        }
        return '';
    }
}