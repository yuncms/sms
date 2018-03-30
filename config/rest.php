<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

return [
    'bootstrap' => [
        'log', 'queue',
    ],
    'controllerMap' => [
        'auth' => 'yuncms\rest\controllers\AuthController',
        'user' => 'yuncms\rest\controllers\UserController',
        'uploader' => 'yuncms\rest\controllers\UploaderController',
        'health' => 'yuncms\web\controllers\HealthController'
    ],
    'components' => [
        'cache' => [
            'keyPrefix' => 'rest',//前缀
        ],
        'request' => [
            'class' => yuncms\web\Request::class,
            'parsers' => [
                'multipart/form-data' => yii\web\MultipartFormDataParser::class,
                'application/json' => yii\web\JsonParser::class,
                'text/json' => yii\web\JsonParser::class,
                'application/xml' => yuncms\web\XmlParser::class,
                'text/xml' => yuncms\web\XmlParser::class
            ],
            'secureProtocolHeaders' => [
                'X-Forwarded-Proto' => ['https'], // Common
                'Front-End-Https' => ['on'], // Microsoft
                'X-Client-Scheme' => ['https'],// Aliyun CDN
                'X-Client-Proto' => ['https'],
            ],
            'ipHeaders' => [
                'X-Forwarded-For',// Common
                'ALI-CDN-REAL-IP',// Aliyun CDN
                'X-Cluster-Client-IP',
            ],
        ],
        'response' => [
            'class' => yuncms\web\Response::class,
        ],
        'urlManager' => [
            'class' => yii\web\UrlManager::class,
            'rules' => [
                'GET ping' => 'health/ping',
            ],
        ],
    ]
];