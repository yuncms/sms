<?php

return [
    'bootstrap' => [
        'log', 'queue',
    ],
    'controllerMap' => [
        'health' => 'yuncms\web\controllers\HealthController'
    ],
    'components' => [
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
                'ALI-CDN-REAL-IP',// Aliyun CDN
                'X-Cluster-Client-IP',
            ],
        ],
        'response' => [
            'class' => yuncms\web\Response::class,
        ],
//        'urlManager' => [
//            'class' => yii\web\UrlManager::class,
//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
//            'rules' => [
//                'GET ping' => 'health/ping',
//            ],
//        ],
    ]
];
