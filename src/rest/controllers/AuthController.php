<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest\controllers;

use Yii;
use yuncms\web\Controller;
use yuncms\oauth2\actions\Token;
use yuncms\oauth2\actions\QRCode;
use yuncms\filters\OAuth2Authorize;
use yuncms\user\models\LoginForm;

/**
 * OAuth2 认证
 * @property bool $isOauthRequest 是否是OAuth2请求
 * @method finishAuthorization
 */
class AuthController extends Controller
{
    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'oauth2Auth' => [
                'class' => OAuth2Authorize::class,
                'only' => ['authorize'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            /**
             * Returns an access token.
             */
            'token' => Token::class,
            /**
             * Returns an access token.
             */
            'qrcode' => QRCode::class,
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
                return $this->finishAuthorization();
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