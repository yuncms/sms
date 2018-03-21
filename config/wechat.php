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
    ]
];