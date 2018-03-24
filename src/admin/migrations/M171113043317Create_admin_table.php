<?php

namespace yuncms\admin\migrations;

use yii\db\Migration;

class M171113043317Create_admin_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%admin}}', [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'username' => $this->string(50)->notNull()->unique()->comment('Username'),
            'email' => $this->string(60)->notNull()->unique()->comment('EMail'),
            'mobile' => $this->string(11)->notNull()->unique()->comment('Mobile'),
            'auth_key' => $this->string(32)->notNull()->comment('Auth Key'),
            'password_hash' => $this->string()->notNull()->comment('Password Hash'),
            'access_token' => $this->string()->unique()->comment('Access Token'),
            'status' => $this->boolean()->defaultValue(true)->comment('Status'),
            'last_login_at' => $this->integer(10)->unsigned()->comment('Last Login At'),
            'created_at' => $this->integer(10)->notNull()->unsigned()->comment('Created At'),
            'updated_at' => $this->integer(10)->notNull()->unsigned()->comment('Updated At'),
        ], $tableOptions);

        //添加默认超级管理员帐户 密码是 123456
        $this->insert('{{%admin}}', [
            'id' => 1,
            'username' => 'admin',
            'email' => 'xutongle@gmail.com',
            'mobile' => '13800138000',
            'auth_key' => '0B8C1dRH1XxKhO15h_9JzaN0OAY9WprZ',
            'password_hash' => '$2y$13$BzPeMPVIFLkiZXwkjJ/HZu0o6Mk0EUQdePC0ufnpzJCzIb4sOrUKK',
            'status' => true,
            'last_login_at' => time(),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%admin}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171113043317Create_admin_table cannot be reverted.\n";

        return false;
    }
    */
}
