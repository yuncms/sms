<?php

return [
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
            'serializer' => yii\queue\serializers\IgbinarySerializer::class,
        ],
        'authClientCollection' => [
            'class' => yii\authclient\Collection::class,
        ],
        'authManager' => [
            'class' => yii\rbac\DbManager::class,
            'cache' => 'cache',
        ],
        'settings' => [
            'class' => yuncms\components\Settings::class,
            'frontCache' => 'cache'
        ],
    ]
];