<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\helpers;

class Html extends \yii\helpers\Html
{
    /**
     * Will take an HTML string and an associative array of key=>value pairs, HTML encode the values and swap them back
     * into the original string using the keys as tokens.
     *
     * @param string $html The HTML string.
     * @param array $variables An associative array of key => value pairs to be applied to the HTML string using `strtr`.
     * @return string The HTML string with the encoded variable values swapped in.
     */
    public static function encodeParams(string $html, array $variables = []): string
    {
        // Normalize the param keys
        $normalizedVariables = [];
        if (is_array($variables)) {
            foreach ($variables as $key => $value) {
                $key = '{' . trim($key, '{}') . '}';
                $normalizedVariables[$key] = static::encode($value);
            }
            $html = strtr($html, $normalizedVariables);
        }
        return $html;
    }
}