<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\helpers;


use Yii;

class NumberHelper
{
    /**
     * Returns the "word" version of a number
     *
     * @param int $num The number
     *
     * @return string The number word, or the original number if it's >= 10
     */
    public static function word(int $num): string
    {
        $numberWordMap = [
            1 => Yii::t('yuncms', 'One'),
            2 => Yii::t('yuncms', 'Two'),
            3 => Yii::t('yuncms', 'Three'),
            4 => Yii::t('yuncms', 'Four'),
            5 => Yii::t('yuncms', 'Five'),
            6 => Yii::t('yuncms', 'Six'),
            7 => Yii::t('yuncms', 'Seven'),
            8 => Yii::t('yuncms', 'Eight'),
            9 => Yii::t('yuncms', 'Nine')
        ];

        if (isset($numberWordMap[$num])) {
            return $numberWordMap[$num];
        }

        return (string)$num;
    }


    /**
     * Returns the uppercase alphabetic version of a number
     *
     * @param int $num The number
     *
     * @return string The alphabetic version of the number
     */
    public static function upperAlpha(int $num): string
    {
        $num--;
        $alpha = '';

        while ($num >= 0) {
            $ascii = ($num % 26) + 65;
            $alpha = chr($ascii) . $alpha;

            $num = (int)($num / 26) - 1;
        }

        return $alpha;
    }

    /**
     * Returns the lowercase alphabetic version of a number
     *
     * @param int $num The number
     *
     * @return string The alphabetic version of the number
     */
    public static function lowerAlpha(int $num): string
    {
        $alpha = static::upperAlpha($num);

        return StringHelper::toLowerCase($alpha);
    }

    /**
     * Returns the uppercase roman numeral version of a number
     *
     * @param int $num The number
     *
     * @return string The roman numeral version of the number
     */
    public static function upperRoman(int $num): string
    {
        $roman = '';

        $map = [
            'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];

        foreach ($map as $k => $v) {
            while ($num >= $v) {
                $roman .= $k;
                $num -= $v;
            }
        }

        return $roman;
    }

    /**
     * Returns the lowercase roman numeral version of a number
     *
     * @param int $num The number
     *
     * @return string The roman numeral version of the number
     */
    public static function lowerRoman(int $num): string
    {
        $roman = static::upperRoman($num);

        return StringHelper::toLowerCase($roman);
    }

    /**
     * Returns the numeric value of a variable.
     *
     * If the variable is an object with a __toString() method, the numeric value of its string representation will be
     * returned.
     *
     * @param mixed $var
     *
     * @return mixed
     */
    public static function makeNumeric($var)
    {
        if (is_numeric($var)) {
            return $var;
        }

        if (is_object($var) && method_exists($var, '__toString')) {
            return static::makeNumeric($var->__toString());
        }

        return (int)!empty($var);
    }
}