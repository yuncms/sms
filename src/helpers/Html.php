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
     * Icon
     *
     * @param string $name
     * @param array $options
     * @return string
     */
    public static function icon($name, $options = [])
    {
        static::addCssClass($options, static::iconName($name));
        $size = ArrayHelper::remove($options, 'size');
        if (!empty($size)) {
            static::addCssClass($options, $size);
        }
        $rotate = ArrayHelper::remove($options, 'rotate');
        if (!empty($rotate)) {
            static::addCssClass($options, $rotate);
        }
        $flip = ArrayHelper::remove($options, 'flip');
        if (!empty($flip)) {
            static::addCssClass($options, $flip);
        }
        $inverse = ArrayHelper::remove($options, 'inverse');
        if (!empty($inverse)) {
            static::addCssClass($options, 'fa-inverse');
        }
        $spin = ArrayHelper::remove($options, 'spin');
        if (!empty($spin)) {
            static::addCssClass($options, 'fa-spin');
        }
        $fw = ArrayHelper::remove($options, 'fw');
        if (!empty($fw)) {
            static::addCssClass($options, 'fa-fw');
        }
        $ul = ArrayHelper::remove($options, 'ul');
        if (!empty($ul)) {
            static::addCssClass($options, 'fa-ul');
        }
        $li = ArrayHelper::remove($options, 'li');
        if (!empty($li)) {
            static::addCssClass($options, 'fa-li');
        }
        $border = ArrayHelper::remove($options, 'border');
        if (!empty($border)) {
            static::addCssClass($options, 'fa-border');
        }
        return Html::tag(ArrayHelper::remove($options, 'tag', 'i'), ArrayHelper::remove($options, 'body'), $options);
    }

    /**
     * @param string $name
     * @return string
     */
    public static function iconName($name)
    {
        $type = ArrayHelper::getValue(explode('-', $name), 0);
        if (empty($type)) {
            return '';
        }
        return $type . ' ' . $name;
    }

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