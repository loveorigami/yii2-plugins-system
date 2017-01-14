<?php

namespace lo\plugins\models;

use lo\plugins\BasePlugin;
use lo\plugins\models\query\ShortcodeQuery;
use lo\plugins\validators\JsonValidator;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%plugins__event}}".
 *
 * @property integer $id
 * @property integer $app_id
 * @property integer $category_id
 * @property integer $plugin_id
 * @property string $tag
 * @property string $tooltip
 * @property string $data
 * @property string $text
 * @property integer $status
 *
 * @property Plugin $plugin
 * @property Category $category
 */
class Shortcode extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plugins__shortcode}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plugin_id', 'app_id', 'handler_class'], 'required'],
            [['plugin_id', 'app_id', 'category_id', 'status'], 'integer'],
            [['text', 'tag', 'tooltip'], 'string'],
            [['data'], JsonValidator::class],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('plugin', 'ID'),
            'app_id' => Yii::t('plugin', 'App ID'),
            'plugin_id' => Yii::t('plugin', 'Plugin ID'),
            'category_id' => Yii::t('plugin', 'Category'),
            'handler_class' => Yii::t('plugin', 'Handler Class'),
            'tag' => Yii::t('plugin', 'Tag'),
            'tooltip' => Yii::t('plugin', 'Tooltip'),
            'data' => Yii::t('plugin', 'Data')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlugin()
    {
        return $this->hasOne(Plugin::class, ['id' => 'plugin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * @inheritdoc
     * @return ShortcodeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ShortcodeQuery(get_called_class());
    }

    /**
     * @param null $appId
     * @return array
     */
    public static function shortcodeList($appId = null)
    {
        if (!$appId) return [];

        return self::find()
            ->alias('s')
            ->innerJoinWith(['plugin p'])
            ->where(['AND',
                ['s.status' => self::STATUS_ACTIVE],
                ['p.status' => Plugin::STATUS_ACTIVE],
                ['s.app_id' => [$appId, BasePlugin::APP_COMMON]]
            ])
            ->all();
    }
}
