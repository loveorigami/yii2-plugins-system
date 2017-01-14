<?php
namespace lo\plugins\migrations;

class m170105_094233_plugins_app extends Migration
{

    public function up()
    {
        $this->createTable($this->tn(self::TBL_APP), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->insert($this->tn(self::TBL_APP), [
            'id' => self::APP_FRONTEND,
            'name' => 'frontend',
        ]);

        $this->insert($this->tn(self::TBL_APP), [
            'id' => self::APP_COMMON,
            'name' => 'common',
        ]);

        $this->insert($this->tn(self::TBL_APP), [
            'id' => self::APP_BACKEND,
            'name' => 'backend',
        ]);

        $this->insert($this->tn(self::TBL_APP), [
            'id' => self::APP_API,
            'name' => 'api',
        ]);

        $this->insert($this->tn(self::TBL_APP), [
            'id' => self::APP_CONSOLE,
            'name' => 'console',
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tn(self::TBL_APP));
    }
}