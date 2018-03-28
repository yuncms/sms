<?php

use yuncms\db\Migration;

/**
 * Handles the creation of table `notification`.
 */
class m180312_032253_create_notification_table extends Migration
{
    public $tableName = '{{%notification}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }

        /**
         * nid: { type: String, unique: true },
         * published: { type: Number, default: Date.now },
         * verb: { type: String },
         * template: { type: String },
         * is_read: { type: Number, index: true, default: 0 },
         * is_pending: { type: Number, index: true, default: 0 },
         * filter: {
         * ftype: { type: String }
         * },
         * sender: { type: Actor },
         * receiver : { type: String , index: true},
         * data: {
         * entity: { type: Entity },
         * source: { type: Entity },
         * target: { type: Entity }
         * }
         */

        /**
         *  $table->uuid('id')->primary();
         * $table->string('type');
         * $table->morphs('notifiable');
         * $table->text('data');
         * $table->timestamp('read_at')->nullable();
         * $table->timestamps();
         */
        $this->createTable($this->tableName, [
            'id' => $this->uuid()->comment('Id'),
            'type' => $this->string()->comment('Type'),
            'notifiable_id' => $this->integer()->unsigned()->comment('Notifiable Id'),
            'notifiable_type' => $this->string()->comment('Notifiable Type'),
            'data' => $this->text(),
            'read_at' => $this->unixTimestamp()->notNull()->comment('Read At'),
            'created_at' => $this->unixTimestamp()->notNull()->comment('Created At'),
            'updated_at' => $this->unixTimestamp()->notNull()->comment('Created At'),
        ], $tableOptions);

        $this->createIndex('notification_index', $this->tableName, ['notifiable_id', 'notifiable_type']);

        $this->createIndex('notification_index', $this->tableName, ['user_id', 'seen']);
        $this->addForeignKey('notification_fk_1', $this->tableName, 'actor', '{{%user}}', 'id', 'CASCADE', 'CASCADE');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
