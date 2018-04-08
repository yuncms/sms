<?php

return [
    'name' => 'YUNCMS',
    'sourceLanguage' => 'en-US',
    'bootstrap' => [
        'log', 'queue',
    ],
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
            'class' => yuncms\i18n\I18N::class,
        ],
        'mailer' => [
            'class' => yii\swiftmailer\Mailer::class,
            'viewPath' => '@yuncms/mail',
        ],
        'settings' => [
            'class' => yuncms\components\Settings::class,
            'frontCache' => 'cache'
        ],
        'path' => [
            'class' => yuncms\services\Path::class,
        ],
        'filesystem' => [
            'class' => yuncms\filesystem\FilesystemManager::class,
            'filesystems' => [
                'local' => [//本地私密存储
                    'class' => yuncms\filesystem\adapters\LocalAdapter::class,
                    'path' => '@root/storage/local'
                ],
                'avatar' => [//头像
                    'class' => yuncms\filesystem\adapters\LocalAdapter::class,
                    'path' => '@root/storage/avatar',
                    'url' => '@web/avatar'
                ],
                'attachment' => [//附件上传
                    'class' => yuncms\filesystem\adapters\LocalAdapter::class,
                    'path' => '@root/storage/attachment',
                    'url' => '@web/uploadfiles'
                ],
            ],
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