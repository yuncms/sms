<?php

$migrationPaths = array_merge(
    require(__DIR__ . '/../../mi.php'),
    [
        //'@yii/caching/migrations',
        //'@yii/log/migrations',
        '@yii/web/migrations',
        '@yii/rbac/migrations',
        '@yii/i18n/migrations',
        '@app/migrations',
    ]
);

return [
    'bootstrap' => [
        'log', 'queue',
    ],
    'components' => [
        'request' => [
            'class' => yuncms\console\Request::class,
        ],
    ],
    'controllerMap' => [
        'task' => [
            'class' => yuncms\console\controllers\TaskController::class,
        ],
        'migrate' => [
            'class' => yuncms\console\controllers\MigrateController::class,
            'templateFile' => '@yuncms/console/views/migrate/migration.php',
            'migrationPath' => $migrationPaths,
        ],
    ],
];