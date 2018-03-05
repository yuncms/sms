<?php

use yii\db\Migration;

/**
 * Handles the creation of table `queue`.
 */
class m180305_014442_create_queue_table extends Migration
{
    public $tableName = '{{%queue}}';

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
            'id' => $this->bigPrimaryKey(),
            'channel' => $this->string()->notNull(),
            'job' => $this->binary()->notNull(),
            'pushed_at' => $this->integer()->unsigned()->notNull(),
            'ttr'=>$this->integer()->notNull()->unsigned(),
            'delay' => $this->integer()->unsigned()->defaultValue(0)->notNull(),
            'priority'=>$this->integer()->unsigned()->notNull()->defaultValue(1024),
            'reserved_at' => $this->integer()->unsigned(),
            'attempt'=>$this->integer()->unsigned(),
            'done_at' => $this->integer()->unsigned(),
        ], $tableOptions);

        $this->createIndex('channel', $this->tableName, 'channel');
        $this->createIndex('reserved_at', $this->tableName, 'reserved_at');
        $this->createIndex('priority', $this->tableName, 'priority');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
