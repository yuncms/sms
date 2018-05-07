<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `volumes`.
 */
class m180507_094747_create_volumes_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%volumes}}';

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
            'id' => $this->primaryKey(),
            'identity' => $this->string(64)->notNull()->unique()->comment('Volume Identity'),
            'name' => $this->string(64)->notNull()->comment('Volume Name'),
            'className' => $this->string()->comment('Volume ClassName'),
            'configuration' => $this->text()->comment('Volume Config'),
            'url' => $this->string()->comment('Url'),
            'pub' => $this->boolean()->defaultValue(false),
            'status' => $this->boolean()->defaultValue(false)->comment('Status'),
            'created_at' => $this->unixTimestamp()->comment('Created At'),//创建时间
            'updated_at' => $this->unixTimestamp()->comment('Updated At'),//更新时间
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
