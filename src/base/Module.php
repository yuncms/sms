<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\base;

use Yii;

/**
 * Class Module
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Module extends \yii\base\Module
{
    /**
     * 获取模块配置
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getSettings($key, $default = null)
    {
        return Yii::$app->settings->get($key, $this->id, $default);
    }

    /**
     * 设置模块配置
     * @param string $key
     * @param mixed $value
     * @param null $type
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function setSettings($key, $value, $type = null)
    {
        return Yii::$app->settings->set($key, $value, $this->id, $type);
    }
}