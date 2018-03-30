<?php
/**
 * @link https://github.com/borodulin/yii2-oauth-server
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-oauth-server/blob/master/LICENSE
 */

namespace yuncms\oauth2\actions;

use Yii;
use yii\base\Action;
use yuncms\web\Response;

/**
 *  获取 二维码 action
 * ```php
 * public function actions()
 * {
 *     return [
 *         'qrcode' => ['class' => 'yuncms\oauth2\actions\QRCode'],
 *     ];
 * }
 * ```
 *
 * @author XuTongle
 */
class QRCode extends Action
{
    const CACHE_PREFIX = 'oauth2.qr.code.login.';

    /**
     * Format of response
     * @var string
     */
    public $format = Response::FORMAT_JSON;

    /**
     * 初始化
     */
    public function init()
    {
        $this->controller->enableCsrfValidation = false;
        Yii::$app->response->format = $this->format;
    }

    /**
     * run
     * @param string $code
     * @throws \yii\base\Exception
     */
    public function run($code = null)
    {
        if (empty($code)) {
            $code = Yii::$app->security->generateRandomString(40);
        }
        $attributes = Yii::$app->cache->getOrSet([self::CACHE_PREFIX, 'code' => $code], function ($cache) use ($code) {
            return [
                'code' => $code,
                'msg' => Yii::t('yuncms', 'Please use App Scan QR code to login.'),
            ];
        }, 120);
        Yii::$app->response->data = $attributes;

    }
}