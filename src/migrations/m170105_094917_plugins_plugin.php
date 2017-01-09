<?php
namespace lo\plugins\migrations;

use lo\plugins\models\Plugin;

class m170105_094917_plugins_plugin extends Migration
{
    const TBL = 'plugin';

    public function up()
    {
        $this->createTable($this->tn(self::TBL), [
            'id' => $this->primaryKey(),
            'status' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'name' => $this->string(),
            'url' => $this->string(),
            'version' => $this->string(),
            'author' => $this->string(),
            'author_url' => $this->string(),
            'text' => $this->text(),
            'hash' => $this->string(32),
        ]);

        $this->createIndex('idx_plugins_item_status', $this->tn(self::TBL), 'status');

        $this->insert($this->tn(self::TBL), [
            'id' => Plugin::CORE_EVENT,
            'status' => Plugin::STATUS_ACTIVE,
            'name' => 'Core Events',
            'url' => '',
            'version' => '1.0',
            'author' => 'Lukyanov Andrey',
            'author_url' => 'https://github.com/loveorigami',
            'text' => 'Core events in our system',
            'hash' => '',
        ]);

        $this->insert($this->tn(self::TBL), [
            'id' => Plugin::CORE_EVENT + 1,
            'status' => Plugin::STATUS_ACTIVE,
            'name' => 'Code Highlighting plugin',
            'url' => 'https://github.com/loveorigami/yii2-plugins-system/tree/master/src/plugins/code',
            'version' => '1.4',
            'author' => 'Lukyanov Andrey',
            'author_url' => 'https://github.com/loveorigami',
            'text' => 'A shortcode for code highlighting in view. Use as [code lang="php"]...content...[/code]',
            'hash' => md5('lo\plugins\plugins\code\Code'),
        ]);
    }

    public function down()
    {
        $this->dropTable($this->tn(self::TBL));
    }

}