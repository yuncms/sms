<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `notification`.
 */
class m180410_092555_create_notification_table extends Migration
{
    /**
     * @var string The table name.
     */
    public $tableName = '{{%notification}}';

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
        // https://segmentfault.com/q/1010000000672529/a-1020000000679702
        $this->createTable($this->tableName, [
            'id' => $this->bigPrimaryKey()->unsigned()->comment('Id'),//通知ID
            'verb' => $this->string(32)->comment('Verb'),//活动图片
            'template' => $this->string()->comment('Template'),//通知类型
            'is_read' => $this->boolean()->defaultValue(false)->comment('Read'),//是否已读
            'is_pending' => $this->boolean()->defaultValue(false)->comment('Pending'),//是否已经推送
            'sender_id' => $this->integer()->unsigned()->comment('Sender Id'),//发送者ID
            'sender_class' => $this->string()->comment('Sender Class'),//发送者模型
            'entity_id' => $this->unsignedInteger()->comment('Entity'),//任务对象
            'source_id' => $this->unsignedInteger()->comment('Source'),//原有任务对象
            'target_id' => $this->unsignedInteger()->comment('Target'),//目标对象
            'receiver' => $this->string()->comment('Receiver'),//接收器
            'publish_at' => $this->integer()->unsigned()->notNull()->comment('Publish At'),//发送时间
        ], $tableOptions);

        $this->createIndex('notification_index', $this->tableName, ['sender_id', 'sender_class']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
