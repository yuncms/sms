<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\controllers;

use Yii;
use yuncms\web\Controller;
use yuncms\oauth2\actions\QRCode;
use yuncms\oauth2\actions\Token;
use yuncms\filters\OAuth2Authorize;
use yuncms\oauth2\frontend\models\LoginForm;

/**
 * OAuth2 认证
 * @property bool $isOauthRequest 是否是OAuth2请求
 * @method finishAuthorization
 */
class AuthController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'oauth2Auth' => [
                'class' => OAuth2Authorize::class,
                'only' => ['authorize'],
            ],
        ];
    }

    public function actions()
    {
        return [
            /**
             * Returns an access token.
             */
            'token' => [
                'class' => Token::class,
            ],
            /**
             * Returns an access token.
             */
            'qrcode' => [
                'class' => QRCode::class,
            ],
        ];
    }

    /**
     * Display login form, signup or something else.
     * AuthClients such as Google also may be used
     */
    public function actionAuthorize()
    {
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if ($this->isOauthRequest) {
                $this->finishAuthorization();
            } else {
                return $this->goBack();
            }
        } else {
            $this->layout = false;
            return $this->render('authorize', [
                'model' => $model,
            ]);
        }
    }
}