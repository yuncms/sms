<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `notification_entity`.
 */
class m180413_034327_create_notification_entity_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%notification_entity}}';

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
            'entity_id' => $this->unsignedInteger()->notNull(),
            'entity_class' => $this->string()->notNull(),
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
