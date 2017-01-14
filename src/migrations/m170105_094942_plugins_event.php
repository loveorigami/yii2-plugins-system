<?php
namespace lo\plugins\migrations;

class m170105_094942_plugins_event extends Migration
{

    public function up()
    {
        $this->createTable($this->tn(self::TBL_EVENT), [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'app_id' => $this->integer()->notNull()->defaultValue(self::APP_FRONTEND),
            'category_id' => $this->integer(),
            'type_id' => $this->integer()->defaultValue(self::TYPE_CORE),
            'plugin_id' => $this->integer()->notNull()->defaultValue(self::EVENTS_CORE),
            'trigger_class' => $this->string(),
            'trigger_event' => $this->string(),
            'handler_class' => $this->string(),
            'handler_method' => $this->string(),
            'data' => $this->text(),
            'text' => $this->text(),
            'pos' => $this->tinyInteger()->notNull()->defaultValue(0),
        ]);

        $this->createIndex('idx_plugins_event_app', $this->tn(self::TBL_EVENT), 'app_id');
        $this->createIndex('idx_plugins_event_type', $this->tn(self::TBL_EVENT), 'type_id');
        $this->createIndex('idx_plugins_event_category', $this->tn(self::TBL_EVENT), 'category_id');
        $this->createIndex('idx_plugins_event_status', $this->tn(self::TBL_EVENT), 'status');
        $this->createIndex('idx_plugins_event_pos', $this->tn(self::TBL_EVENT), 'pos');
        $this->createIndex('idx_plugins_event_trigger', $this->tn(self::TBL_EVENT), ['trigger_class', 'trigger_event']);
        $this->createIndex('idx_plugins_event_handler', $this->tn(self::TBL_EVENT), ['handler_class', 'handler_method'], true);

        $this->addForeignKey(
            $this->fk(self::TBL_EVENT, self::TBL_PLUGIN),
            $this->tn(self::TBL_EVENT), 'plugin_id',
            $this->tn(self::TBL_PLUGIN), 'id',
            'CASCADE', 'CASCADE'
        );

        $this->addForeignKey(
            $this->fk(self::TBL_EVENT, self::TBL_APP),
            $this->tn(self::TBL_EVENT), 'app_id',
            $this->tn(self::TBL_APP), 'id',
            'RESTRICT', 'RESTRICT'
        );

        $this->addForeignKey(
            $this->fk(self::TBL_EVENT, self::TBL_CATEGORY),
            $this->tn(self::TBL_EVENT), 'category_id',
            $this->tn(self::TBL_CATEGORY), 'id',
            'RESTRICT', 'RESTRICT'
        );

        $this->insert($this->tn(self::TBL_EVENT), [
            'id' => 1,
            'app_id' => self::APP_FRONTEND,
            'type_id' => self::TYPE_CORE,
            'plugin_id' => self::EVENTS_CORE,
            'category_id' => self::CAT_SEO,
            'trigger_class' => 'yii\web\View',
            'trigger_event' => 'beginPage',
            'handler_class' => 'lo\plugins\core\SeoHandler',
            'handler_method' => 'updateTitle',
            'status' => self::EVENTS_ACTIVE
        ]);

        $this->insert($this->tn(self::TBL_EVENT), [
            'id' => 2,
            'app_id' => self::APP_FRONTEND,
            'type_id' => self::TYPE_PLUGIN,
            'plugin_id' => self::EVENTS_CORE + 1, // Hello, world
            'category_id' => self::CAT_PLUGINS,
            'trigger_class' => 'yii\web\Response',
            'trigger_event' => 'afterPrepare',
            'handler_class' => 'lo\plugins\core\helloworld\HelloWorld',
            'handler_method' => 'hello',
            'data' => '{"search":"Hello, world!","replace":"Hello, Yii!","color":"#FFDB51"}',
            'status' => self::EVENTS_ACTIVE
        ]);

    }

    public function down()
    {
        $this->dropForeignKey(
            $this->fk(self::TBL_EVENT, self::TBL_PLUGIN),
            $this->tn(self::TBL_EVENT)
        );

        $this->dropForeignKey(
            $this->fk(self::TBL_EVENT, self::TBL_APP),
            $this->tn(self::TBL_EVENT)
        );

        $this->dropForeignKey(
            $this->fk(self::TBL_EVENT, self::TBL_CATEGORY),
            $this->tn(self::TBL_EVENT)
        );

        $this->dropTable($this->tn(self::TBL_EVENT));
    }

}