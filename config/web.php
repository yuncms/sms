<?php

return [
    'bootstrap' => [
        'log', 'queue',
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
        'urlManager' => [
            'class' => yii\web\UrlManager::class,
        ],
        'assetManager' => [//前端资源压缩
            'linkAssets' => PHP_OS == 'WINNT' ? false : true,
            'appendTimestamp' => true,
        ],
    ]
];
