<?php

use yii\db\Schema;
use yii\db\Migration;

class m150423_145822_Option extends Migration
{
    public function up()
    {
        $this->createTable('option', [
            'id' => Schema::TYPE_PK,
            'store_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . '(128) NOT NULL',
            'value' => Schema::TYPE_TEXT,
        ]);
        $this->createIndex('store_name', 'option', ['store_id', 'name'], true);

        if (!strpos(get_class($this->db->getSchema()), 'sqlite')) {
            $this->addForeignKey('option_store', 'option', 'store_id', 'store', 'id', 'CASCADE', 'CASCADE');
        }
    }

    public function down()
    {
        $this->dropTable('option');
    }

}
