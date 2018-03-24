<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
return [
    'bootstrap' => ['log', yuncms\admin\Bootstrap::class],
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
            'identityClass' => yuncms\admin\models\Admin::class
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => yuncms\admin\Module::class
        ]
    ]
];