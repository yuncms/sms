<?php

return [
    'bootstrap' => [
        'log', 'queue',
    ],
    'components' => [
        'request' => [
            'class' => yuncms\console\Request::class,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'default' => [
                    'class' => yii\log\DbTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'controllerMap' => [
        'task' => [
            'class' => yuncms\console\controllers\TaskController::class,
        ],
        'migrate' => [
            'class' => yuncms\console\controllers\MigrateController::class,
            'templateFile' => '@yuncms/console/views/migrate/migration.php',
            'migrationPath' => [
                '@vendor/yuncms/framework/migrations',
                //'@yii/caching/migrations',
                //'@yii/log/migrations',
                '@yii/web/migrations',
                '@yii/rbac/migrations',
                '@yii/i18n/migrations',
                '@app/migrations',
            ],
        ],
    ],
];