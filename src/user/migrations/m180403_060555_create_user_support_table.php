<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `user_support`.
 */
class m180403_060555_create_user_support_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%user_support}}';

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
        /**
         * 赞表
         */
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            'user_id' => $this->integer()->unsigned()->notNull()->comment('User ID'),
            'model_id' => $this->integer()->notNull()->comment('Model ID'),
            'model_class' => $this->string(100)->notNull()->comment('Model Class'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
            'updated_at' => $this->integer()->unsigned()->notNull()->comment('Updated At'),
        ], $tableOptions);

        $this->addForeignKey('support_fk_1', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('support_index', $this->tableName, ['model_id', 'model_class'], false);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
