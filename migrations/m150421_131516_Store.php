<?php

use yii\db\Schema;
use yii\db\Migration;

class m150421_131516_Store extends Migration
{
    public function up()
    {
        $this->createTable('store', [
            'id' => Schema::TYPE_PK,
            'domain' => Schema::TYPE_STRING . '(128) NOT NULL',
            'access_token' => Schema::TYPE_STRING . '(64) NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
        ]);
    }

    public function down()
    {
        $this->dropTable('store');
    }
    
}
