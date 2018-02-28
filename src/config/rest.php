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
                'multipart/form-data' => yii\web\MultipartFormDataParser::class,
                'application/json' => yii\web\JsonParser::class,
                'text/json' => yii\web\JsonParser::class,
                'application/xml' => yuncms\web\XmlParser::class,
                'text/xml' => yuncms\web\XmlParser::class
            ]
        ],
        'response' => [
            'class' => yuncms\web\Response::class,
        ],
        'user' => [
            'class' => yuncms\web\User::class,
            'identityClass' => yuncms\models\User::class,
            'enableSession' => false,
            'enableAutoLogin' => false,
            'loginUrl' => null,


        ],
    ]
];