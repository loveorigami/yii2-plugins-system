<?php

namespace lo\plugins\models;

use lo\plugins\helpers\JsonHelper;
use lo\plugins\models\query\EventQuery;
use lo\plugins\validators\CallableValidator;
use lo\plugins\validators\ClassNameValidator;
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
 * @property string $trigger_class
 * @property string $trigger_event
 * @property string $handler_class
 * @property string $handler_method
 * @property string $text
 * @property string $data
 * @property integer $status
 * @property Plugin $plugin
 * @property Category $category
 */
class Event extends ActiveRecord
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plugins__event}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'trigger_class', 'trigger_event', 'handler_class', 'handler_method'], 'required'],
            [['plugin_id', 'category_id', 'status', 'pos'], 'integer'],
            [['trigger_class', 'trigger_event', 'handler_class', 'handler_method'], 'string', 'max' => 255],
            [['pos'], 'default', 'value' => 1],
            [['data'], JsonValidator::class],
            [['handler_class', 'trigger_class'], ClassNameValidator::class],
            [['handler_method'], CallableValidator::class, 'callableAttribute' => 'handler_class']
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
            'trigger_class' => Yii::t('plugin', 'Trigger Class'),
            'trigger_event' => Yii::t('plugin', 'Trigger Event'),
            'handler_class' => Yii::t('plugin', 'Handler Class'),
            'handler_method' => Yii::t('plugin', 'Handler Method'),
            'data' => Yii::t('plugin', 'Data'),
            'pos' => Yii::t('plugin', 'Position'),
            'status' => Yii::t('plugin', 'Status'),
            'text' => Yii::t('plugin', 'Text'),
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
     * @return EventQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new EventQuery(get_called_class());
    }

    /**
     * @return string
     */
    public function getTriggerClass()
    {
        return $this->trigger_class;
    }

    /**
     * @return string
     */
    public function getTriggerEvent()
    {
        return $this->trigger_event;
    }

    /**
     * @return array
     */
    public function getHandler()
    {
        return [
            [$this->handler_class, $this->handler_method],
            JsonHelper::decode($this->data)
        ];
    }
}
