<?php
namespace lo\plugins\migrations;

use lo\plugins\components\BasePlugin;
use lo\plugins\models\Event;
use lo\plugins\models\Plugin;

class m170105_094942_plugins_event extends Migration
{
    const TBL = 'event';
    const TBL_PLUGIN = 'plugin';
    const TBL_APP = 'app';

    public function up()
    {
        $this->createTable($this->tn(self::TBL), [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'app_id' => $this->integer()->notNull()->defaultValue(BasePlugin::APP_FRONTEND),
            'plugin_id' => $this->integer()->notNull()->defaultValue(Plugin::CORE_EVENT),
            'trigger_class' => $this->string(),
            'trigger_event' => $this->string(),
            'handler_class' => $this->string(),
            'handler_method' => $this->string(),
            'data' => $this->text(),
            'text' => $this->text(),
            'pos' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_plugins_event_app', $this->tn(self::TBL), 'app_id');
        $this->createIndex('idx_plugins_event_status', $this->tn(self::TBL), 'status');
        $this->createIndex('idx_plugins_event_pos', $this->tn(self::TBL), 'pos');
        $this->createIndex('idx_plugins_event_trigger', $this->tn(self::TBL), ['trigger_class', 'trigger_event']);
        $this->createIndex('idx_plugins_event_handler', $this->tn(self::TBL), ['handler_class', 'handler_method'], true);

        $this->addForeignKey(
            $this->fk(self::TBL, self::TBL_PLUGIN),
            $this->tn(self::TBL), 'plugin_id',
            $this->tn(self::TBL_PLUGIN), 'id',
            'CASCADE', 'CASCADE'
        );

        $this->addForeignKey(
            $this->fk(self::TBL, self::TBL_APP),
            $this->tn(self::TBL), 'app_id',
            $this->tn(self::TBL_APP), 'id',
            'RESTRICT', 'RESTRICT'
        );

        $this->insert($this->tn(self::TBL), [
            'id' => 1,
            'plugin_id' => 2, // Code Highlighting
            'app_id' => BasePlugin::APP_FRONTEND,
            'trigger_class' => 'yii\base\View',
            'trigger_event' => 'afterRender',
            'handler_class' => 'lo\plugins\plugins\code\Code',
            'handler_method' => 'shortcode',
            'data' => '{"style":"github","lang":"php"}',
            'status' => Event::STATUS_ACTIVE
        ]);

    }

    public function down()
    {
        $this->dropForeignKey(
            $this->fk(self::TBL, self::TBL_PLUGIN),
            $this->tn(self::TBL)
        );

        $this->dropForeignKey(
            $this->fk(self::TBL, self::TBL_APP),
            $this->tn(self::TBL)
        );

        $this->dropTable($this->tn(self::TBL));
    }

}