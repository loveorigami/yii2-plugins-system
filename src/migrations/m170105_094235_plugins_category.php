<?php
namespace lo\plugins\migrations;

class m170105_094235_plugins_category extends Migration
{

    public function up()
    {
        $this->createTable($this->tn(self::TBL_CATEGORY), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->insert($this->tn(self::TBL_CATEGORY), [
            'id' => self::CAT_SHORTCODES,
            'name' => 'Shortcodes'
        ]);

        $this->insert($this->tn(self::TBL_CATEGORY), [
            'id' => self::CAT_PLUGINS,
            'name' => 'Plugins'
        ]);

        $this->insert($this->tn(self::TBL_CATEGORY), [
            'id' => self::CAT_SEO,
            'name' => 'SEO'
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tn(self::TBL_CATEGORY));
    }
}