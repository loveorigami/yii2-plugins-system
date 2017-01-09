<?php
namespace lo\plugins\migrations;

use lo\plugins\BasePlugin;

class m170105_094233_plugins_app extends Migration
{
    const TBL = 'app';

    public function up()
    {
        $this->createTable($this->tn(self::TBL), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->insert($this->tn(self::TBL), [
            'id' => BasePlugin::APP_FRONTEND,
            'name' => 'frontend',
        ]);

        $this->insert($this->tn(self::TBL), [
            'id' => BasePlugin::APP_COMMON,
            'name' => 'common',
        ]);

        $this->insert($this->tn(self::TBL), [
            'id' => BasePlugin::APP_BACKEND,
            'name' => 'backend',
        ]);

        $this->insert($this->tn(self::TBL), [
            'id' => BasePlugin::APP_API,
            'name' => 'api',
        ]);

        $this->insert($this->tn(self::TBL), [
            'id' => BasePlugin::APP_CONSOLE,
            'name' => 'console',
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tn(self::TBL));
    }
}