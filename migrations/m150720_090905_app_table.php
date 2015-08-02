<?php

use yii\db\Schema;
use yii\db\Migration;

class m150720_090905_app_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%plugins__app}}", [
            'id' => Schema::TYPE_PK,
            'name'  => Schema::TYPE_STRING,
        ], $tableOptions);

        $this->insert('{{%plugins__app}}', [
            'id' => 1,
            'name' => 'frontend',
        ]);

        $this->insert('{{%plugins__app}}', [
            'id' => 2,
            'name' => 'common',
        ]);

        $this->insert('{{%plugins__app}}', [
            'id' => 3,
            'name' => 'backend',
        ]);


    }

    public function down()
    {
        echo "m150720_090905_app_table cannot be reverted.\n";

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
