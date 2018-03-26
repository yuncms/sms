<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
return [
    'bootstrap' => ['log', 'queue'],
    'layout' => '@yuncms/admin/views/layouts/main',
    //'defaultRoute' => 'dashboard',
    'as access' => [
        'class' => 'yuncms\filters\BackendAccessControl',
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf_backend',
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
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