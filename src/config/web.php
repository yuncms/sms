<?php

return [
    'bootstrap' => [
        'log', 'queue',
    ],
    'components' => [
        'request' => [
            'class' => yuncms\web\Request::class,
            'ipHeaders' => [
                'Client-IP',
                'X-Forwarded-For',
                'X-Forwarded',
                'X-Cluster-Client-IP',
                'X-REAL-IP',
                'Forwarded-For',
                'Forwarded',
                'RemoteIp'
            ],
            'secureProtocolHeaders' => [
                'X-Forwarded-Proto' => ['https'],
                'Front-End-Https' => ['on'],
                'X-CLIENT-SCHEME' => ['https'],
                'X-Client-Proto' => ['https'],
            ],
        ],
        'response' => [
            'class' => yuncms\web\Response::class,
        ],
        'urlManager' => [
            'class' => yii\web\UrlManager::class,
        ],
        'user' => [
            'class' => yuncms\web\User::class,
            'identityClass' => yuncms\models\User::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['/user/security/login'],
        ],
    ]
];