<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\oauth2;

use yuncms\filters\auth\OAuth2TokenAuth;

/**
 * Class Controller
 *
 * @author Tongle Xu <xutongle@gmail.com>
 * @since 3.0
 */
class Controller extends \yii\rest\Controller
{
    /**
     * 初始化 API 控制器验证
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => OAuth2TokenAuth::class,
        ];
        return $behaviors;
    }
}