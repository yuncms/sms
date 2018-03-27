<?php

return [
    'bootstrap' => [
        'log', 'queue',
    ],
    'controllerMap' => [
        'health' => 'yuncms\web\controllers\HealthController'
    ],
    'components' => [
        'cache' => [
            'keyPrefix' => 'web',       //前缀
        ],
        'request' => [
            'class' => yuncms\web\Request::class,
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
