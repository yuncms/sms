<?php
/**
 * @link https://github.com/borodulin/yii2-oauth2-server
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-oauth2-server/blob/master/LICENSE
 */

namespace yuncms\oauth2\grant\types;

use yuncms\oauth2\GrantType;
use yuncms\oauth2\models\OAuth2AccessToken;
use yuncms\oauth2\models\OAuth2RefreshToken;

/**
 * ```
 * POST /token HTTP/1.1
 * Host: server.example.com
 * Authorization: Basic czZCaGRSa3F0MzpnWDFmQmF0M2JW
 * Content-Type: application/x-www-form-urlencoded
 *
 * response_type=client_credentials&client_id=100000&client_secret=A3dA3ddj3wdA3ddj3wAA3ddj3w3ddj3wj3w
 * ```
 * @author Xutl
 *
 * @property null|\yuncms\user\models\User $user
 */
class ClientCredentials extends GrantType
{
    /** @var  \yuncms\user\models\User */
    private $_user;

    /**
     * Value MUST be set to "client_credentials"
     * @var string
     */
    public $grant_type;

    /**
     * Access Token Scope
     * @link https://tools.ietf.org/html/rfc6749#section-3.3
     * @var string
     */
    public $scope;

    /**
     * @var string
     */
    public $client_id;

    /**
     * @var string
     */
    public $client_secret;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grant_type', 'client_id', 'client_secret'], 'required'],
            ['grant_type', 'required', 'requiredValue' => 'client_credentials'],
            [['client_id'], 'string', 'max' => 80],
            [['client_id'], 'validateClientId'],
            [['client_secret'], 'validateClientSecret'],
            [['scope'], 'validateScope'],
        ];
    }

    /**
     * @inheritdoc
     * @return array
     * @throws \yii\base\Exception
     * @throws \yuncms\oauth2\Exception
     */
    public function getResponseData()
    {
        /** @var \yuncms\user\models\User $identity */
        $identity = $this->getUser();
        $accessToken = OAuth2AccessToken::createAccessToken([
            'client_id' => $this->client_id,
            'user_id' => $identity->id,
            'expires' => $this->accessTokenLifetime + time(),
            'scope' => $this->scope,
        ]);
        $refreshToken = OAuth2RefreshToken::createRefreshToken([
            'client_id' => $this->client_id,
            'user_id' => $identity->id,
            'expires' => $this->refreshTokenLifetime + time(),
            'scope' => $this->scope,
        ]);
        return [
            'access_token' => $accessToken->access_token,
            'expires_in' => $this->accessTokenLifetime,
            'token_type' => $this->tokenType,
            'scope' => $this->scope,
            'refresh_token' => $refreshToken->refresh_token,
        ];
    }


    /**
     * Finds user
     *
     * @return \yuncms\user\models\User|null
     * @throws \yuncms\oauth2\Exception
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = $this->getClient()->user;
        }
        return $this->_user;
    }
}
