<?php

use yii\db\Schema;
use yii\db\Migration;

class m150422_122856_Option extends Migration
{
    public function up()
    {
        $this->createTable('option', [
            'name' => Schema::TYPE_STRING . ' NOT NULL',
            'value' => Schema::TYPE_STRING,
        ]);
        $this->addPrimaryKey('', 'option', ['name']);
    }

    public function down()
    {
        $this->dropTable('option');
    }
    
}
