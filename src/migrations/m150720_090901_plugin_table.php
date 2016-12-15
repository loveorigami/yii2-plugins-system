<?php

use yii\db\Schema;
use yii\db\Migration;

class m150720_090901_plugin_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%plugins__item}}", [
            'id' => Schema::TYPE_PK,
            'handler_class' => Schema::TYPE_STRING,
			'name'  => Schema::TYPE_STRING,
            'url'  => Schema::TYPE_STRING,
            'version' => Schema::TYPE_STRING,
            'text'=> Schema::TYPE_TEXT,
			'author'=> Schema::TYPE_STRING,
			'author_url'=> Schema::TYPE_STRING,
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150720_090901_plugin_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
