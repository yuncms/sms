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
            'parsers' => [
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
                'application/xml' => 'yuncms\web\XmlParser',
                'text/xml' => 'yuncms\web\XmlParser'
            ]
        ],
        'response' => [
            'class' => yuncms\web\Response::class,
        ],
        'user' => [
            'class' => yuncms\web\User::class,
            'enableSession' => false,
            'loginUrl' => null,
            'enableAutoLogin' => false,
            'identityClass' => 'yuncms\models\User',
        ],
    ]
];