<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\db;

use yii\db\ColumnSchemaBuilder;

class Migration extends \yii\db\Migration
{
    /**
     * Shortcut for creating a uuid column
     *
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function uuid(): ColumnSchemaBuilder
    {
        return $this->char(36)->notNull()->defaultValue('0');
    }

    /**
     * Shortcut for creating a user id column
     *
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function userId(): ColumnSchemaBuilder
    {
        return $this->integer()->unsigned();
    }

    /**
     * Shortcut for creating an unsigned tiny integer column.
     * @param int $length column size or precision definition.
     * This parameter will be ignored if not supported by the DBMS.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function unsignedTinyInteger($length = null): ColumnSchemaBuilder
    {
        return $this->tinyInteger($length)->unsigned();
    }

    /**
     * Shortcut for creating an unsigned small integer column.
     * @param int $length column size or precision definition.
     * This parameter will be ignored if not supported by the DBMS.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function unsignedSmallInteger($length = null): ColumnSchemaBuilder
    {
        return $this->smallInteger($length)->unsigned();
    }

    /**
     * Shortcut for creating unsigned integer column.
     * @param int $length column size or precision definition.
     * This parameter will be ignored if not supported by the DBMS.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function unsignedInteger($length = null): ColumnSchemaBuilder
    {
        return $this->integer($length)->unsigned();
    }

    /**
     * Shortcut for creating unsigned big integer column.
     * @param int $length column size or precision definition.
     * This parameter will be ignored if not supported by the DBMS.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function unsignedBigInteger($length = null): ColumnSchemaBuilder
    {
        return $this->bigInteger($length)->unsigned();
    }

    /**
     * Shortcut for creating mac address column.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function macAddress(): ColumnSchemaBuilder
    {
        return $this->string(17);
    }

    /**
     * Shortcut for creating ip address column.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function ipAddress(): ColumnSchemaBuilder
    {
        return $this->string(45);
    }

    /**
     * Shortcut for creating a timestamps column
     *
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function unixTimestamp(): ColumnSchemaBuilder
    {
        return $this->unsignedInteger(10);
    }

}