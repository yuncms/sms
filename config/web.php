<?php

return [
    'bootstrap' => [
        'log', 'queue',
    ],
    'controllerMap' => [
        'health' => 'yuncms\web\controllers\HealthController',
        'upload' => 'yuncms\web\controllers\UploadController'
    ],
    'components' => [
        'cache' => [
            'keyPrefix' => 'web',       //前缀
        ],
        'user' => [
            'identityClass' => yuncms\user\models\User::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['/user/security/login'],
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
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
    ],
    'modules' => [
        'user' => [
            'class' => yuncms\user\Module::class
        ],
        'oauth2' => [
            'class' => yuncms\oauth2\Module::class
        ]
    ]
];
