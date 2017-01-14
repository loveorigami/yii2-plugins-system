<?php
namespace lo\plugins\migrations;

class m170105_094958_plugins_shortcode extends Migration
{

    public function up()
    {
        $this->createTable($this->tn(self::TBL_SHORTCODE), [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'app_id' => $this->integer()->notNull()->defaultValue(self::APP_FRONTEND),
            'category_id' => $this->integer(),
            'plugin_id' => $this->integer()->notNull()->defaultValue(self::EVENTS_CORE),
            'handler_class' => $this->string(),
            'tag' => $this->string(),
            'tooltip' => $this->string(),
            'data' => $this->text(),
            'text' => $this->text(),
        ]);

        $this->createIndex('idx_plugins_sh_app', $this->tn(self::TBL_SHORTCODE), 'app_id');
        $this->createIndex('idx_plugins_sh_plugin', $this->tn(self::TBL_SHORTCODE), 'plugin_id');
        $this->createIndex('idx_plugins_sh_category', $this->tn(self::TBL_SHORTCODE), 'category_id');
        $this->createIndex('idx_plugins_sh_status', $this->tn(self::TBL_SHORTCODE), 'status');

        $this->addForeignKey(
            $this->fk(self::TBL_SHORTCODE, self::TBL_PLUGIN),
            $this->tn(self::TBL_SHORTCODE), 'plugin_id',
            $this->tn(self::TBL_PLUGIN), 'id',
            'CASCADE', 'CASCADE'
        );

        $this->addForeignKey(
            $this->fk(self::TBL_SHORTCODE, self::TBL_APP),
            $this->tn(self::TBL_SHORTCODE), 'app_id',
            $this->tn(self::TBL_APP), 'id',
            'RESTRICT', 'RESTRICT'
        );

        $this->addForeignKey(
            $this->fk(self::TBL_SHORTCODE, self::TBL_CATEGORY),
            $this->tn(self::TBL_SHORTCODE), 'category_id',
            $this->tn(self::TBL_CATEGORY), 'id',
            'RESTRICT', 'RESTRICT'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            $this->fk(self::TBL_SHORTCODE, self::TBL_PLUGIN),
            $this->tn(self::TBL_SHORTCODE)
        );

        $this->dropForeignKey(
            $this->fk(self::TBL_SHORTCODE, self::TBL_APP),
            $this->tn(self::TBL_SHORTCODE)
        );

        $this->dropForeignKey(
            $this->fk(self::TBL_SHORTCODE, self::TBL_CATEGORY),
            $this->tn(self::TBL_SHORTCODE)
        );

        $this->dropTable($this->tn(self::TBL_SHORTCODE));
    }

}