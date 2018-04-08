<?php

$config = [
    'components' => [
        'cache' => [
            'keyPrefix' => 'rest',       //前缀
        ],
        'request' => [
            'class' => yuncms\console\Request::class,
        ],
    ],
    'controllerMap' => [
        'task' => [
            'class' => yuncms\console\controllers\TaskController::class,
        ],
        'user' => [
            'class' => yuncms\console\controllers\UserController::class,
        ],
        'oauth2' => [
            'class' => yuncms\console\controllers\OAuth2Controller::class,
        ],
        'migrate' => [
            'class' => yuncms\console\controllers\MigrateController::class,
            'templateFile' => '@yuncms/console/views/migrate/migration.php',
            'generatorTemplateFiles' => [
                'create_table' => '@yuncms/console/views/migrate/createTableMigration.php',
                'drop_table' => '@yuncms/console/views/migrate/dropTableMigration.php',
                'add_column' => '@yuncms/console/views/migrate/addColumnMigration.php',
                'drop_column' => '@yuncms/console/views/migrate/dropColumnMigration.php',
                'create_junction' => '@yuncms/console/views/migrate/createTableMigration.php',
            ],
        ],
    ],
];

return yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/main.php'),
    $config
);