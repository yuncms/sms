<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
return [
    'bootstrap' => ['log', yuncms\backend\Bootstrap::class],
    'components' => [
        'authManager' => [
            'class' => yuncms\backend\RbacManager::class,
            'cache' => 'cache',
        ],
    ]
];