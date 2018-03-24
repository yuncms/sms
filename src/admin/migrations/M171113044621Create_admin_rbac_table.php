<?php

namespace yuncms\admin\migrations;

use yii\db\Migration;

class M171113044621Create_admin_rbac_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%admin_auth_rule}}', [
            'name' => $this->string(64)->notNull()->unique(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        $this->createTable('{{%admin_auth_item}}', [
            'name' => $this->string(64)->notNull()->unique(),
            'type' => $this->integer()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);
        $this->createIndex('idx-auth_item-type', '{{%admin_auth_item}}', 'type');
        $this->addForeignKey('{{%admin_auth_item_ibfk_1}}', '{{%admin_auth_item}}', 'rule_name', '{{%admin_auth_rule}}', 'name', 'SET NULL', 'CASCADE');

        $this->createTable('{{%admin_auth_item_child}}', [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('', '{{%admin_auth_item_child}}', ['parent', 'child']);
        $this->addForeignKey('{{%admin_auth_item_child_ibfk_1}}', '{{%admin_auth_item_child}}', 'parent', '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%admin_auth_item_child_ibfk_2}}', '{{%admin_auth_item_child}}', 'child', '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');

        $this->createTable('{{%admin_auth_assignment}}', [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->string(64)->notNull(),
            'created_at' => $this->integer(),
        ], $tableOptions);
        $this->addPrimaryKey('', '{{%admin_auth_assignment}}', ['item_name', 'user_id']);
        $this->addForeignKey('{{%admin_auth_assignment_ibfk_1}}', '{{%admin_auth_assignment}}', 'item_name', '{{%admin_auth_item}}', 'name', 'CASCADE', 'CASCADE');

        //添加规则
        $this->insert('{{%admin_auth_rule}}', ['name' => 'RouteRule', 'data' => 'O:33:"yuncms\admin\components\RouteRule":3:{s:4:"name";s:9:"RouteRule";s:9:"createdAt";i:1482143421;s:9:"updatedAt";i:1482143421;}', 'created_at' => time(), 'updated_at' => time()]);
        $this->insert('{{%admin_auth_rule}}', ['name' => 'GuestRule', 'data' => 'O:33:"yuncms\admin\components\GuestRule":3:{s:4:"name";s:9:"GuestRule";s:9:"createdAt";i:1482143535;s:9:"updatedAt";i:1482143421;}', 'created_at' => time(), 'updated_at' => time()]);

        //添加角色
        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'description', 'rule_name', 'created_at', 'updated_at'], [
            ['Super Administrator', 1, '超级管理员对系统有不受限制的完全访问权。', 'RouteRule', time(), time()],
            ['Administrator', 1, '防止管理员进行有意或无意的系统范围的更改，但是可以执行大部分管理操作。', 'RouteRule', time(), time()],
        ]);

        //添加路由
        $this->batchInsert('{{%admin_auth_item}}', ['name', 'type', 'created_at', 'updated_at'], [
            ['/*', 2, time(), time()],
            ['/site/*', 2, time(), time()],
            ['/site/index', 2, time(), time()],
            ['/admin/security/logout', 2, time(), time()],
        ]);

        //给超级管理员组授权
        $this->insert('{{%admin_auth_item_child}}', ['parent' => 'Super Administrator', 'child' => '/*']);
        $this->insert('{{%admin_auth_assignment}}', ['item_name' => 'Super Administrator', 'user_id' => 1, 'created_at' => time()]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%admin_auth_assignment}}');
        $this->dropTable('{{%admin_auth_item_child}}');
        $this->dropTable('{{%admin_auth_item}}');
        $this->dropTable('{{%admin_auth_rule}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171113044621Create_admin_rbac_table cannot be reverted.\n";

        return false;
    }
    */
}
