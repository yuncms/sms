<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\controllers;

use yuncms\rest\Controller;

/**
 * 短信验证码
 * @package api\controllers
 */
class SmsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'verify-code' => [
                'class' => 'yuncms\sms\captcha\CaptchaAction',
                'sendJobClass' => 'yuncms\sms\jobs\CaptchaJob',
                'minLength' => 5,
                'maxLength' => 7,
                'fixedVerifyCode' => YII_ENV_TEST ? '12345' : null,
            ],
        ];
    }
}