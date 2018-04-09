<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\rest;

use yii\rest\Serializer;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\auth\QueryParamAuth;
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
     * @var string|array the configuration for creating the serializer that formats the response data.
     */
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items',
    ];

    /**
     * @var array 认证方法
     */
    public $authMethods = [
        HttpBasicAuth::class,
        HttpBearerAuth::class,
        HttpHeaderAuth::class,
        QueryParamAuth::class,
        OAuth2TokenAuth::class,
    ];

    /**
     * 初始化 API 控制器验证
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => $this->authMethods,
        ];
        return $behaviors;
    }
}