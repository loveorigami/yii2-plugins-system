<?php

use yii\db\Schema;
use yii\db\Migration;

class m150720_091726_event_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable("{{%plugins__event}}", [
            'id' => Schema::TYPE_PK,
			'app_id' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1',
			'plugin_id' => Schema::TYPE_INTEGER . ' NOT NULL',
			'trigger_class'  => Schema::TYPE_STRING,
            'trigger_event'  => Schema::TYPE_STRING,
            'handler_method'=> Schema::TYPE_STRING,
            'data'=> Schema::TYPE_TEXT,
            'pos'=> Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
        ], $tableOptions);
		
		$this->addForeignKey(
            'fk_plugins_event_plugins_item',
            '{{%plugins__event}}',
            'plugin_id',
            '{{%plugins__item}}',
            'id',
            'cascade',
            'cascade'
        );
		
		$this->addForeignKey(
            'fk_plugins_event_plugins_app',
            '{{%plugins__event}}',
            'app_id',
            '{{%plugins__app}}',
            'id',
            'cascade',
            'cascade'
        );

        $this->insert('{{%plugins__item}}', [
            'id' => 1,
            'handler_class' => 'lo\plugins\plugins\code\Code',
            'name' => 'Code Highlighting plugin',
            'status' => 1,
        ]);

        $this->insert('{{%plugins__event}}', [
            'id' => 1,
            'app_id' => 1, // frontend
            'plugin_id' => 1, // Code Highlighting
            'trigger_class' => 'yii\base\View',
            'trigger_event' => 'afterRender',
            'handler_method' => 'shortCode',
            'data' => '{"style":"github","lang":"php"}',
            'status' => 1,
        ]);
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk_plugins_event_plugins_item',
            '{{%plugins__event}}'
        );
		
        $this->dropForeignKey(
            'fk_plugins_event_plugins_app',
            '{{%plugins__event}}'
        );
		
        $this->dropTable('{{%plugins__event}}');
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
