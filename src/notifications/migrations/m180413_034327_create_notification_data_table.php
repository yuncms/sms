<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `notification_data`.
 */
class m180413_034327_create_notification_data_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%notification_data}}';

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
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned(),
            'type' => $this->string(20),
            'entity _id' => $this->unsignedInteger()->notNull(),
            'entity _class' => $this->string()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
