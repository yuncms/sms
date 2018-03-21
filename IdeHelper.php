<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

/**
 * Yii bootstrap file.
 * Used for enhanced IDE code autocompletion.
 *
 * @property-read xutl\aliyun\Aliyun $aliyun The aliyun component. This property is read-only. Extended component.
 * @property-read xutl\wechat\Wechat $wechat The redis component. This property is read-only. Extended component.
 * @property-read xutl\tim\Tim $im The im component. This property is read-only. Extended component.
 * @property-read xutl\qcloud\QCloud $qcloud The qcloud component. This property is read-only. Extended component.
 * @property-read xutl\dingtalk\DingTalk $dingTalk The green component. This property is read-only. Extended component.
 */
class Yii extends \yii\BaseYii
{
    /**
     * @var yii\base\Application|yii\web\Application|yii\console\Application|yuncms\console\Application|yuncms\web\Application the application instance
     */
    public static $app;
}