<?php

use yii\db\Schema;
use yii\db\Migration;

class m150423_145822_StoreOptions extends Migration
{
    public function up()
    {
        $this->createTable('store_option', [
            'id' => Schema::TYPE_PK,
            'store_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'value' => Schema::TYPE_STRING,
        ]);
        $this->createIndex('store_name', 'store_option', ['store_id', 'name'], true);
        $this->addForeignKey('store_option_store', 'store_option', 'store_id', 'store', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('store_option');
    }

}
