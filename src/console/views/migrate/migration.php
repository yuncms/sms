<?php
/**
 * This view is used by console/controllers/MigrateController.php
 * The following variables are available in this view:
 */
/* @var $className string the new migration class name without namespace */
/* @var $namespace string the new migration class namespace */

echo "<?php\n";
if (!empty($namespace)) {
    echo "\nnamespace {$namespace};\n";
}
?>

use yuncms\db\Migration;

/**
 * Class <?= $className . "\n" ?>
 */
class <?= $className ?> extends Migration
{
    /*
     * @var string the table name.
     */
    public $tableName;

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey()->unsigned()->comment('ID'),
            //'user_id' => $this->unsignedInteger()->comment('User ID'),
            'status' => $this->unsignedTinyInteger(1)->defaultValue(0)->comment('Status'),
            'published_at' => $this->unixTimestamp()->comment('Published At'),
            'created_at' => $this->unixTimestamp()->notNull()->comment('Created At'),
            'updated_at' => $this->unixTimestamp()->notNull()->comment('Updated At'),
        ], $tableOptions);

        //$this->addForeignKey('test_fk', $this->tableName, 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "<?= $className ?> cannot be reverted.\n";

        return false;
    }
    */
}
