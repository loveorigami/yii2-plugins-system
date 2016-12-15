<?php

namespace lo\plugins\models;

use Yii;

/**
 * This is the model class for table "{{%plugins__item}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $url
 * @property string $version
 * @property string $text
 * @property string $author
 * @property string $author_url
 * @property integer $status
 *
 * @property PluginsEvent[] $pluginsEvents
 */
class Item extends \yii\db\ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plugins__item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','handler_class'], 'required'],
            // a1 needs to be unique
            ['handler_class', 'unique'],
            [['text'], 'string'],
            [['status'], 'integer'],
            [['name', 'url', 'version', 'author', 'author_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('plugin', 'ID'),
            'handler_class' => Yii::t('plugin', 'Handler Class'),
            'name' => Yii::t('plugin', 'Name'),
            'url' => Yii::t('plugin', 'Url'),
            'version' => Yii::t('plugin', 'Version'),
            'text' => Yii::t('plugin', 'Text'),
            'author' => Yii::t('plugin', 'Author'),
            'author_url' => Yii::t('plugin', 'Author Url'),
            'status' => Yii::t('plugin', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::class, ['plugin_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return ItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ItemQuery(get_called_class());
    }
}
