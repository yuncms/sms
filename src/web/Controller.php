<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\web;

use Yii;
use yii\web\Response as YiiResponse;
use Da\QrCode\QrCode;

/**
 * Class Controller
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Controller extends \yii\web\Controller
{
    /**
     * Sets the response format of the given data as JSONP.
     *
     * @param mixed $data The data that should be formatted.
     * @return Response A response that is configured to send `$data` formatted as JSON.
     * @see YiiResponse::$format
     * @see YiiResponse::FORMAT_JSONP
     * @see JsonResponseFormatter
     */
    public function asJsonP($data): YiiResponse
    {
        $response = Yii::$app->getResponse();
        $response->data = $data;
        $response->format = YiiResponse::FORMAT_JSONP;
        return $response;
    }

    /**
     * Sets the response format of the given data as RAW.
     *
     * @param mixed $data The data that should *not* be formatted.
     * @return YiiResponse A response that is configured to send `$data` without formatting.
     * @see YiiResponse::$format
     * @see YiiResponse::FORMAT_RAW
     */
    public function asRaw($data): YiiResponse
    {
        $response = Yii::$app->getResponse();
        $response->data = $data;
        $response->format = YiiResponse::FORMAT_RAW;
        return $response;
    }

    /**
     * 输出二维码
     * @param string $data
     * @return mixed
     */
    public function asQrCode($data)
    {
        $response = Yii::$app->getResponse();
        $response->format = YiiResponse::FORMAT_RAW;
        $headers = $response->getHeaders();
        $qrCode = new QrCode($data);
        $headers->setDefault('Content-Type', $qrCode->getContentType());
        $response->data = $qrCode->writeString();
        return $response;
    }
}