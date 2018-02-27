<?php

return [
    'bootstrap' => [
        'log', 'queue',
    ],
    'components' => [
        'request' => yuncms\console\Request::class,
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => yuncms\console\controllers\MigrateController::class,
            'templateFile' => '@yuncms/console/views/migrate/migration.php',
            'migrationPath' => [
                '@vendor/yuncms/framework/migrations',
                //'@yii/caching/migrations',
                //'@yii/log/migrations',
                '@yii/web/migrations',
                //'@yii/rbac/migrations',
                '@yii/i18n/migrations',
                '@app/migrations',
            ],
        ],
    ],
];