<?php

namespace yuncms\admin\migrations;

use yii\db\Migration;

class M171113043435Create_admin_menu_table extends Migration
{

    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%admin_menu}}', [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'name' => $this->string(128)->notNull()->comment('Name'),
            'parent' => $this->integer()->unsigned()->comment('Parent'),
            'route' => $this->string()->comment('Route'),
            'icon' => $this->string(30)->comment('Icon'),
            'visible' => $this->boolean()->defaultValue(true)->comment('Visible'),
            //排序
            'sort' => $this->smallInteger()->defaultValue(99)->comment('Sort'),
            'data' => $this->text()->comment('Data')
        ], $tableOptions);
        $this->addForeignKey('{{%admin_menu_fk_1}}', '{{%admin_menu}}', 'parent', '{{%admin_menu}}', 'id', 'SET NULL', 'CASCADE');

        $this->batchInsert('{{%admin_menu}}', ['id', 'name', 'parent', 'route', 'icon', 'sort', 'data'], [
            //一级主菜单
            [1, '控制台', NULL, '/site/index', 'fa-th-large', 1, NULL],
            [2, '核心设置', NULL, NULL, 'fa-cog', 2, NULL],
            [3, '数据管理', NULL, NULL, 'fa-wrench', 3, NULL],
            [4, '运营中心', NULL, NULL, 'fa-bar-chart-o', 4, NULL],
            [5, '用户管理', NULL, NULL, 'fa-user', 5, NULL],
            [6, '网站管理', NULL, NULL, 'fa-bars', 6, NULL],
            [7, '财务管理', NULL, NULL, 'fa-cny', 7, NULL],
            [8, '模块管理', NULL, NULL, 'fa-th', 8, NULL],
            [9, '模板管理', NULL, NULL, 'fa-laptop', 9, NULL],

            //核心设置子菜单
            [21, '站点设置', 2, '/admin/setting/setting', 'fa-gears', 1, NULL],
            [22, '管理员管理', 2, '/admin/admin/index', 'fa-user', 2, NULL],
            [24, '角色管理', 2, '/admin/role/index', 'fa-group', 3, NULL],
            [25, '权限管理', 2, '/admin/permission/index', 'fa-certificate', 4, NULL],
            [26, '路由管理', 2, '/admin/route/index', 'fa-cloud', 5, NULL],
            [27, '规则管理', 2, '/admin/rule/index', 'fa-key', 6, NULL],
            [28, '菜单管理', 2, '/admin/menu/index', 'fa-wrench', 7, NULL],
            //[30, '附件设置', 2, '/attachment/attachment/setting', 'fa-cog', 8, NULL],

            // [40, '地区管理', 3, '/area/index', 'fa-globe', 1, NULL],
            //[43, '敏感词管理', 3, '/admin/ban-word/index', 'fa-exclamation-triangle', 2, NULL],
        ]);

        //隐藏的子菜单[隐藏的子菜单不设置id字段，使用自增]//从10000开始
        $this->batchInsert('{{%admin_menu}}', ['id', 'name', 'parent', 'route', 'visible', 'sort'], [
            [10000, '管理员查看', 22, '/admin/admin/view', 0, NULL],
        ]);
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['更新管理员', 22, '/admin/admin/update', 0, NULL], ['授权设置', 22, '/admin/assignment/view', 0, NULL],
            ['角色查看', 24, '/admin/role/view', 0, NULL], ['创建角色', 24, '/admin/role/create', 0, NULL], ['更新角色', 24, '/admin/role/update', 0, NULL],
            ['权限查看', 25, '/admin/permission/view', 0, NULL], ['创建权限', 25, '/admin/permission/create', 0, NULL], ['更新权限', 25, '/admin/permission/update', 0, NULL],
            ['路由查看', 26, '/admin/route/view', 0, NULL], ['创建路由', 26, '/admin/route/create', 0, NULL],
            ['规则查看', 27, '/admin/rule/view', 0, NULL], ['创建规则', 27, '/admin/rule/create', 0, NULL], ['更新规则', 27, '/admin/rule/update', 0, NULL],
            ['菜单查看', 28, '/admin/menu/view', 0, NULL], ['创建菜单', 28, '/admin/menu/create', 0, NULL], ['更新菜单', 28, '/admin/menu/update', 0, NULL],
            //['创建地区', 40, '/area/create', 0, NULL], ['更新地区', 40, '/area/update', 0, NULL],
            //['敏感词查看', 43, '/admin/ban-word/view', 0, NULL], ['创建敏感词', 43, '/admin/ban-word/create', 0, NULL], ['更新敏感词', 43, '/admin/ban-word/update', 0, NULL],
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%admin_menu}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171113043435Create_admin_menu_table cannot be reverted.\n";

        return false;
    }
    */
}
