<?php

return [
    'bootstrap' => [
        'log', 'queue',
    ],
    'components' => [
        'request' => yuncms\console\Request::class,
    ],
    'controllerMap' => [
        'cron' => [
            'class' => yuncms\console\controllers\CronController::class,
        ],
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