<?php

namespace lo\plugins\migrations;

class m170105_094960_seo_clear_url extends Migration
{

    public function up()
    {
        $this->insert($this->tn(self::TBL_EVENT), [
            'id' => 5,
            'app_id' => self::APP_FRONTEND,
            'plugin_id' => self::EVENTS_CORE, // Hello, world
            'category_id' => self::CAT_SEO,
            'trigger_class' => 'yii\base\Application',
            'trigger_event' => 'beforeRequest',
            'handler_class' => 'lo\plugins\core\SeoHandler',
            'handler_method' => 'clearUrl',
            'data' => '{}',
            'status' => self::EVENTS_ACTIVE
        ]);
    }

    public function down()
    {
        $this->delete($this->tn(self::TBL_EVENT), ['id' => 5]);
    }

}