<?php

return [
    'name' => 'YUNCMS',
    'sourceLanguage' => 'en-US',
    'components' => [
        'formatter' => [
            'class' => yii\i18n\Formatter::class,
            'dateFormat' => 'yyyy-MM-dd',
            'datetimeFormat' => 'yyyy-MM-dd HH:mm:ss',
            'timeFormat' => 'HH:mm:ss',
        ],
        'db' => [
            'class' => yii\db\Connection::class,
            'charset' => 'utf8',
            'tablePrefix' => 'yun_',
        ],
        'cache' => [
            'class' => yii\caching\DummyCache::class,
        ],
        'queue' => [
            'class' => yii\queue\sync\Queue::class,
        ],
        'authClientCollection' => [
            'class' => yii\authclient\Collection::class,
        ],
        'i18n' => [
            'translations' => [
                'yuncms' => [
                    'class' => yii\i18n\PhpMessageSource::class,
                    'basePath' => '@vendor/yuncms/framework/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ]
        ],
        'mailer' => [
            'class' => yii\swiftmailer\Mailer::class,
            'viewPath' => '@yuncms/mail',
        ],
        'settings' => [
            'class' => yuncms\components\Settings::class,
            'frontCache' => 'cache'
        ],
        'filesystem' => [
            'class' => yuncms\filesystem\FilesystemManager::class,
            'filesystems' => [
                'local' => [
                    'class' => yuncms\filesystem\adapters\LocalAdapter::class,
                    'path' => '@root/storage/local'
                ],
                'avatar' => [
                    'class' => yuncms\filesystem\adapters\LocalAdapter::class,
                    'path' => '@root/storage/avatar'
                ],
                'attachment' => [
                    'class' => yuncms\filesystem\adapters\LocalAdapter::class,
                    'path' => '@root/storage/attachment'
                ],
            ],
        ],
        'payment' => [
            'class' => yuncms\payment\PaymentManager::class,
        ],
        'notification' => [
            'class' => yuncms\notifications\NotificationManager::class,
        ],
        'sms' => [
            'class' => yuncms\sms\Sms::class,
            'defaultStrategy' => yuncms\sms\strategies\OrderStrategy::class
        ],
        'snowflake' => [
            'class' => yuncms\base\Snowflake::class,
            'workerId' => 0,
            'dataCenterId' => 0,
        ],
    ]
];