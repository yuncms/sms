<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
return [
    'bootstrap' => ['log'],
    'layout' => '@yuncms/admin/views/layouts/main',
    'as access' => [
        'class' => 'yuncms\filters\BackendAccessControl',
    ],
    'components' => [
        'authManager' => [
            'class' => yuncms\rbac\DbManager::class,
            'cache' => 'cache',
            'cacheTag' => 'backend.rbac',
            'itemTable' => '{{%admin_auth_item}}',
            'itemChildTable' => '{{%admin_auth_item_child}}',
            'assignmentTable' => '{{%admin_auth_assignment}}',
            'ruleTable' => '{{%admin_auth_rule}}'
        ],
        'user' => [
            'identityClass' => yuncms\admin\models\Admin::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['/admin/security/login'],
            'identityCookie' => [
                'name' => '_identity_backend',
                'httpOnly' => true
            ],
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => yuncms\admin\Module::class
        ]
    ]
];