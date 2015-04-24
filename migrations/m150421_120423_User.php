<?php

use yii\db\Schema;
use yii\db\Migration;

class m150421_120423_User extends Migration
{
    public function up()
    {
        $this->createTable('user', [
            'id' => Schema::TYPE_PK,
            'username' => Schema::TYPE_STRING . ' NOT NULL',
            'auth_key' => Schema::TYPE_STRING . '(32) NOT NULL',
            'password_hash' => Schema::TYPE_STRING . ' NOT NULL',
            'password_reset_token' => Schema::TYPE_STRING,
            'email' => Schema::TYPE_STRING . ' NOT NULL',
            'fullname' => Schema::TYPE_STRING,
        ]);

        $this->insert('user', [
            'username' => 'admin',
            'password_hash' => '$2y$13$zUS9jxBlgP7qX8E0Xa7jEeKTFyqUVe9HmVTfojWcJnaejWWbQ6hyu', // "admin"
            'auth_key' => 'SECRET_KEY',
            'email' => 'admin@example.com',
            'fullname' => 'Root Admin',
        ]);
    }

    public function down()
    {
        $this->dropTable('user');
    }
    
}
