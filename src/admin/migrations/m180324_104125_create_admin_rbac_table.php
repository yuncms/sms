<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `admin_rbac`.
 */
class m180324_104125_create_admin_rbac_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $time = time();

        $this->createTable('{{%admin_auth_rule}}', [
            'name' => $this->string(64)->notNull()->unique(),
            'data' => $this->text(),
            'created_at' => $this->unixTimestamp(),
            'updated_at' => $this->unixTimestamp()
        ], $tableOptions);

        $this->createTable('{{%admin_auth_item}}', [
            'name' => $this->string(64)->notNull()->unique(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->unixTimestamp(),
            'updated_at' => $this->unixTimestamp(),
        ], $tableOptions);
        $this->createIndex('idx-auth_item-type', '{{%admin_auth_item}}', 'type');
        $this->addForeignKey('admin_auth_item_fk_1', '{{%admin_auth_item}}', 'rule_name', '{{%admin_auth_rule}}', 'name', 'SET NULL', 'CASCADE');

        $this->createTable('{{%admin_auth_item_child}}', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('', '{{%admin_auth_item_child}}', ['parent', 'child']);
        $this->addForeignKey('admin_auth_item_child_fk_1', '{{%admin_auth_item_child}}', 'parent', '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('admin_auth_item_child_fk_2', '{{%admin_auth_item_child}}', 'child', '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');

        $this->createTable('{{%admin_auth_assignment}}', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->unixTimestamp(),
        ], $tableOptions);
        $this->addPrimaryKey('', '{{%admin_auth_assignment}}', ['item_name', 'user_id']);
        $this->addForeignKey('{{%admin_auth_assignment_ibfk_1}}', '{{%admin_auth_assignment}}', 'item_name', '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');

        //添加规则
        $this->insert('{{%admin_auth_rule}}', ['name' => 'GuestRule', 'data' => 'O:21:"yuncms\rbac\GuestRule":3:{s:4:"name";s:9:"GuestRule";s:9:"createdAt";i:1522062274;s:9:"updatedAt";i:1522062274;}', 'created_at' => time(), 'updated_at' => time()]);
        $this->insert('{{%admin_auth_rule}}', ['name' => 'RouteRule', 'data' => 'O:21:"yuncms\rbac\RouteRule":3:{s:4:"name";s:9:"RouteRule";s:9:"createdAt";i:1522062288;s:9:"updatedAt";i:1522062288;}', 'created_at' => time(), 'updated_at' => time()]);

        //添加角色
        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            ['Super Administrator', 1, '超级管理员对系统有不受限制的完全访问权。', 'RouteRule', $time, $time],
            ['Administrator', 1, '防止管理员进行有意或无意的系统范围的更改，但是可以执行大部分管理操作。', 'RouteRule', $time, $time],
        ]);

        //添加路由
        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'created_at', 'updated_at'], [
            ['/*', 2, $time, $time],
            ['/site/*', 2, $time, $time],
            ['/site/index', 2, $time, $time],
            ['/admin/security/logout', 2, $time, $time],
            ['/admin/admin/*', 2, $time, $time],
            ['/admin/admin/create', 2, $time, $time],
            ['/admin/admin/delete', 2, $time, $time],
            ['/admin/admin/index', 2, $time, $time],
            ['/admin/admin/update', 2, $time, $time],
            ['/admin/admin/view', 2, $time, $time],
            ['/admin/assignment/*', 2, $time, $time],
            ['/admin/assignment/assign', 2, $time, $time],
            ['/admin/assignment/index', 2, $time, $time],
            ['/admin/assignment/revoke', 2, $time, $time],
            ['/admin/assignment/view', 2, $time, $time],
            ['/admin/attachment/*', 2, $time, $time],
            ['/admin/attachment/batch-delete', 2, $time, $time],
            ['/admin/attachment/delete', 2, $time, $time],
            ['/admin/attachment/index', 2, $time, $time],
            ['/admin/attachment/setting', 2, $time, $time],
            ['/admin/attachment/view', 2, $time, $time],
            ['/admin/menu/*', 2, $time, $time],
            ['/admin/menu/auto-complete', 2, $time, $time],
            ['/admin/menu/create', 2, $time, $time],
            ['/admin/menu/delete', 2, $time, $time],
            ['/admin/menu/index', 2, $time, $time],
            ['/admin/menu/position', 2, $time, $time],
            ['/admin/menu/update', 2, $time, $time],
            ['/admin/menu/view', 2, $time, $time],
            ['/admin/oauth2/*', 2, $time, $time],
            ['/admin/oauth2/batch-delete', 2, $time, $time],
            ['/admin/oauth2/create', 2, $time, $time],
            ['/admin/oauth2/delete', 2, $time, $time],
            ['/admin/oauth2/index', 2, $time, $time],
            ['/admin/oauth2/update', 2, $time, $time],
            ['/admin/oauth2/view', 2, $time, $time],
            ['/admin/permission/*', 2, $time, $time],
            ['/admin/permission/assign', 2, $time, $time],
            ['/admin/permission/create', 2, $time, $time],
            ['/admin/permission/delete', 2, $time, $time],
            ['/admin/permission/index', 2, $time, $time],
            ['/admin/permission/remove', 2, $time, $time],
            ['/admin/permission/update', 2, $time, $time],
            ['/admin/permission/view', 2, $time, $time],
            ['/admin/queue/*', 2, $time, $time],
            ['/admin/role/*', 2, $time, $time],
            ['/admin/role/assign', 2, $time, $time],
            ['/admin/role/create', 2, $time, $time],
            ['/admin/role/delete', 2, $time, $time],
            ['/admin/role/index', 2, $time, $time],
            ['/admin/role/remove', 2, $time, $time],
            ['/admin/role/update', 2, $time, $time],
            ['/admin/role/view', 2, $time, $time],
            ['/admin/route/*', 2, $time, $time],
            ['/admin/route/assign', 2, $time, $time],
            ['/admin/route/create', 2, $time, $time],
            ['/admin/route/index', 2, $time, $time],
            ['/admin/route/refresh', 2, $time, $time],
            ['/admin/route/remove', 2, $time, $time],
            ['/admin/rule/*', 2, $time, $time],
            ['/admin/rule/create', 2, $time, $time],
            ['/admin/rule/delete', 2, $time, $time],
            ['/admin/rule/index', 2, $time, $time],
            ['/admin/rule/update', 2, $time, $time],
            ['/admin/rule/view', 2, $time, $time],
            ['/admin/security/*', 2, $time, $time],
            ['/admin/security/captcha', 2, $time, $time],
            ['/admin/setting/*', 2, $time, $time],
            ['/admin/setting/setting', 2, $time, $time],
            ['/admin/user/*', 2, $time, $time],
            ['/admin/user/block', 2, $time, $time],
            ['/admin/user/confirm', 2, $time, $time],
            ['/admin/user/create', 2, $time, $time],
            ['/admin/user/delete', 2, $time, $time],
            ['/admin/user/index', 2, $time, $time],
            ['/admin/user/settings', 2, $time, $time],
            ['/admin/user/update', 2, $time, $time],
            ['/admin/user/update-profile', 2, $time, $time],
            ['/admin/user/view', 2, $time, $time],
            ['/upload/*', 2, $time, $time],
            ['/upload/file-upload', 2, $time, $time],
            ['/upload/files-upload', 2, $time, $time],
            ['/upload/image-upload', 2, $time, $time],
            ['/upload/images-upload', 2, $time, $time],
        ]);

        //新建权限
        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'rule_name', 'created_at', 'updated_at'], [
            ['核心设置', 2, 'RouteRule', $time, $time],
            ['站点设置', 2, 'RouteRule', $time, $time],
            ['管理员管理', 2, 'RouteRule', $time, $time],
            ['角色管理', 2, 'RouteRule', $time, $time],
            ['权限管理', 2, 'RouteRule', $time, $time],
            ['路由管理', 2, 'RouteRule', $time, $time],
            ['规则管理', 2, 'RouteRule', $time, $time],
            ['菜单管理', 2, 'RouteRule', $time, $time],
            ['附件上传', 2, 'RouteRule', $time, $time],
            ['附件设置', 2, 'RouteRule', $time, $time],
            ['附件管理', 2, 'RouteRule', $time, $time],
            ['用户管理', 2, 'RouteRule', $time, $time],
        ]);

        //给超级管理员组授权
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Super Administrator', 'child' => '/*']);
        $this->insert('{{%admin_auth_assignment}}', ['item_name' => 'Super Administrator', 'user_id' => 1, 'created_at' => $time]);

        //管理授权
        $this->batchInsert('{{%admin_auth_item_child}}', ['parent', 'child'], [
            ['核心设置', '站点设置'],
            ['核心设置', '管理员管理'],
            ['核心设置', '角色管理'],
            ['核心设置', '权限管理'],
            ['核心设置', '路由管理'],
            ['核心设置', '规则管理'],
            ['核心设置', '菜单管理'],
            ['核心设置', '附件设置'],
            ['站点设置', '/admin/setting/setting'],
            //['管理员管理', '/admin/admin/*'],
            ['管理员管理', '/admin/admin/create'],
            ['管理员管理', '/admin/admin/delete'],
            ['管理员管理', '/admin/admin/index'],
            ['管理员管理', '/admin/admin/update'],
            ['管理员管理', '/admin/admin/view'],
            //['菜单管理', '/admin/menu/*'],
            ['菜单管理', '/admin/menu/auto-complete'],
            ['菜单管理', '/admin/menu/create'],
            ['菜单管理', '/admin/menu/delete'],
            ['菜单管理', '/admin/menu/index'],
            ['菜单管理', '/admin/menu/position'],
            ['菜单管理', '/admin/menu/update'],
            ['菜单管理', '/admin/menu/view'],
            //['规则管理', '/admin/rule/*'],
            ['规则管理', '/admin/rule/create'],
            ['规则管理', '/admin/rule/delete'],
            ['规则管理', '/admin/rule/index'],
            ['规则管理', '/admin/rule/update'],
            ['规则管理', '/admin/rule/view'],
            //['角色管理', '/admin/role/*'],
            ['角色管理', '/admin/role/assign'],
            ['角色管理', '/admin/role/create'],
            ['角色管理', '/admin/role/delete'],
            ['角色管理', '/admin/role/index'],
            ['角色管理', '/admin/role/remove'],
            ['角色管理', '/admin/role/update'],
            ['角色管理', '/admin/role/view'],
            //['路由管理', '/admin/route/*'],
            ['路由管理', '/admin/route/assign'],
            ['路由管理', '/admin/route/create'],
            ['路由管理', '/admin/route/index'],
            ['路由管理', '/admin/route/refresh'],
            ['路由管理', '/admin/route/remove'],
            ['附件设置', '/admin/attachment/setting'],
            //['权限管理', '/admin/permission/*'],
            ['权限管理', '/admin/permission/assign'],
            ['权限管理', '/admin/permission/create'],
            ['权限管理', '/admin/permission/delete'],
            ['权限管理', '/admin/permission/index'],
            ['权限管理', '/admin/permission/remove'],
            ['权限管理', '/admin/permission/update'],
            ['权限管理', '/admin/permission/view'],

            ['附件上传', '/upload/*'],
            ['附件上传', '/upload/file-upload'],
            ['附件上传', '/upload/files-upload'],
            ['附件上传', '/upload/image-upload'],
            ['附件上传', '/upload/images-upload'],

            ['用户管理', '/admin/user/block'],
            ['用户管理', '/admin/user/confirm'],
            ['用户管理', '/admin/user/create'],
            ['用户管理', '/admin/user/delete'],
            ['用户管理', '/admin/user/index'],
            ['用户管理', '/admin/user/settings'],
            ['用户管理', '/admin/user/update'],
            ['用户管理', '/admin/user/update-profile'],
            ['用户管理', '/admin/user/view'],

            ['附件管理', '/admin/attachment/index'],
            ['附件管理', '/admin/attachment/batch-delete'],
            ['附件管理', '/admin/attachment/delete'],
            ['附件管理', '/admin/attachment/view'],
        ]);

        //给 Administrator 授权

        $this->batchInsert('{{%admin_auth_item_child}}', ['parent', 'child'], [
            ['Administrator', '/site/index'],
            ['Administrator', '用户管理'],
            ['Administrator', '附件上传'],
            ['Administrator', '附件管理'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%admin_auth_assignment}}');
        $this->dropTable('{{%admin_auth_item_child}}');
        $this->dropTable('{{%admin_auth_item}}');
        $this->dropTable('{{%admin_auth_rule}}');
    }
}
