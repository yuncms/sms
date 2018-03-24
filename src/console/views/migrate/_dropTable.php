<?php

/**
 * Creates a call for the method `yii\db\Migration::dropTable()`.
 */
/* @var $foreignKeys array the foreign keys */

echo $this->render('_dropForeignKeys', [
    'foreignKeys' => $foreignKeys,
]) ?>
        $this->dropTable($this->tableName);
