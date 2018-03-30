<?php
/**
 * @link https://github.com/borodulin/yii2-oauth2-server
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-oauth2-server/blob/master/LICENSE
 */

namespace yuncms\oauth2\grant\types;

use Yii;
use yuncms\oauth2\Exception;
use yuncms\oauth2\GrantType;
use yuncms\oauth2\models\OAuth2AccessToken;

/**
 * For example, the client makes the following HTTP request using
 * transport-layer security (with extra line breaks for display purposes
 * only):
 *
 * ```
 * POST /token HTTP/1.1
 * Host: server.example.com
 * Authorization: Basic czZCaGRSa3F0MzpnWDFmQmF0M2JW
 * Content-Type: application/x-www-form-urlencoded
 *
 * response_type=qrcode&code=johndoe
 * ```
 *
 * @link https://tools.ietf.org/html/rfc6749#section-4.3
 * @author Dmitry Fedorenko
 */
class QRCode extends GrantType
{
    private $_accessToken;

    /**
     * Value MUST be set to "qrcode".
     * @var string
     */
    public $grant_type;

    /**
     * The refresh token issued to the client.
     * @var string
     */
    public $access_token;

    /**
     * @var string 二维码扫描结果
     */
    public $code;

    /**
     * The scope of the access request as described by Section 3.3.
     * @var string
     */
    public $scope;

    /**
     *
     * @var string
     */
    public $client_id;

    /**
     * @var string
     */
    public $arg;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['client_id', 'grant_type', 'access_token', 'code'], 'required'],
            [['client_id'], 'string', 'max' => 80],
            [['arg'], 'string', 'max' => 1000],
            [['access_token', 'code'], 'string', 'max' => 40],
            [['client_id'], 'validateClientId'],
            [['access_token'], 'validateAccessToken'],
        ];
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function getResponseData()
    {
        $accessToken = $this->getAccessToken();
        $accessToken = OAuth2AccessToken::createAccessToken([
            'client_id' => $this->client_id,
            'user_id' => $accessToken->user->id,
            'expires' => $this->accessTokenLifetime + time(),
            'scope' => $this->scope,
        ]);
        $attributes = [
            'access_token' => $accessToken->access_token,
            'expires_in' => $this->accessTokenLifetime,
            'token_type' => $this->tokenType,
            'scope' => $this->scope,
            'code' => $this->code,
            'msg' => Yii::t('yuncms', 'Login successful.'),
            'arg' => $this->arg,
        ];
        Yii::$app->cache->set([\yuncms\oauth2\actions\QRCode::CACHE_PREFIX, 'code' => $this->code], $attributes, 120);
        return $attributes;
    }

    /**
     * @throws Exception
     */
    public function validateAccessToken()
    {
        $this->getAccessToken();
    }

    /**
     * 获取 AccessToken 实例
     * @return OAuth2AccessToken
     * @throws Exception
     */
    public function getAccessToken()
    {
        if (is_null($this->_accessToken)) {
            if (empty($this->access_token)) {
                $this->errorServer(Yii::t('yuncms', 'The request is missing "access_token" parameter'));
            }
            if (!$this->_accessToken = OAuth2AccessToken::findOne(['access_token' => $this->access_token])) {
                $this->errorServer(Yii::t('yuncms', 'The Access Token is invalid'));
            }
        }
        return $this->_accessToken;
    }

    public function getAccess_token()
    {
        return $this->getRequestValue('access_token');
    }

    public function getCode()
    {
        return $this->getRequestValue('code');
    }
}
