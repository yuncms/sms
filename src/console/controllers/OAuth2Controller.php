<?php
/**
 * @link https://github.com/borodulin/yii2-oauth2-server
 * @copyright Copyright (c) 2015 Andrey Borodulin
 * @license https://github.com/borodulin/yii2-oauth2-server/blob/master/LICENSE
 */

namespace yuncms\console\controllers;

use yii\console\Controller;
use yuncms\oauth2\models\OAuth2AuthorizationCode;
use yuncms\oauth2\models\OAuth2RefreshToken;
use yuncms\oauth2\models\OAuth2AccessToken;

/**
 * @author Andrey Borodulin
 */
class OAuth2Controller extends Controller
{
    /**
     * Clean up expired token
     */
    public function actionClear()
    {
        OAuth2AuthorizationCode::deleteAll(['<', 'expires', time()]);
        OAuth2RefreshToken::deleteAll(['<', 'expires', time()]);
        OAuth2AccessToken::deleteAll(['<', 'expires', time()]);
    }
}