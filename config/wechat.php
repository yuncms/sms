<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

$config = [
    'controllerMap' => [
        'health' => 'yuncms\web\controllers\HealthController',
        'upload' => 'yuncms\web\controllers\UploadController'
    ],
    'components' => [
        'cache' => [
            'keyPrefix' => 'wechat',       //前缀
        ],
        'request' => [
            'class' => yuncms\wechat\Request::class,
            'secureProtocolHeaders' => [
                'X-Forwarded-Proto' => ['https'], // Common
                'Front-End-Https' => ['on'], // Microsoft
                'X-Client-Scheme' => ['https'],// Aliyun CDN
                'X-Client-Proto' => ['https'],
            ],
            'ipHeaders' => [
                'X-Forwarded-For',// Common
                'X-Cluster-Client-IP',
                'ALI-CDN-REAL-IP',// Aliyun CDN
                'Client-IP',
                'X-Forwarded',
                'Forwarded-For',
                'Forwarded',
            ],
        ],
        'response' => [
            'class' => yuncms\wechat\Response::class,
        ],
        'urlManager' => [
            'class' => yii\web\UrlManager::class,
            'rules' => [
                'GET ping' => 'health/ping',
            ],
        ],
    ]
];

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/main.php'),
    $config
);