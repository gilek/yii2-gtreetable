<?php

use yii\db\Schema;
use yii\db\Migration;

class m150206_075816_create_tree_table extends Migration
{
    public $tableName = '{{%tree}}';
    
    public function up()
    {
        $this->createTable($this->tableName, [
            'id' => Schema::TYPE_PK,
            'root' => Schema::TYPE_INTEGER . ' UNSIGNED DEFAULT NULL',
            'lft' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'rgt' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'level' => Schema::TYPE_SMALLINT . ' UNSIGNED NOT NULL',
            'type' => Schema::TYPE_STRING . '(64) NOT NULL',
            'name' => Schema::TYPE_STRING . '(128) NOT NULL',
        ]);
        $this->createIndex('root', $this->tableName, 'root');
        $this->createIndex('lft', $this->tableName, 'lft');
        $this->createIndex('rgt', $this->tableName, 'rgt');
        $this->createIndex('level', $this->tableName, 'level');
        
        $this->insert($this->tableName, [
            'root' => 1,
            'lft' => 1,
            'rgt' => 2,
            'level' => 0,
            'type' => 'default',
            'name' => 'Main node'
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
