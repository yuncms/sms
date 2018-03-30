<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\widgets;


class Menu extends \yii\widgets\Menu
{
    /**
     * Creates a widget instance and runs it.
     * The widget rendering result is returned by this method.
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @return string the rendering result of the widget.
     */
    public static function widget($config = [])
    {
        try {
            return parent::widget($config);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}