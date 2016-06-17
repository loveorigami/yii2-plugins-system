<?php

namespace lo\plugins\models;

use Yii;
use lo\plugins\helpers\JsonValidator;

/**
 * This is the model class for table "{{%plugins__event}}".
 *
 * @property integer $id
 * @property integer $plugin_id
 * @property string $trigger_class
 * @property string $trigger_event
 * @property string $handler_class
 * @property string $handler_method
 * @property integer $status
 *
 * @property PluginsItem $plugin
 */
class Event extends \yii\db\ActiveRecord
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
            [['plugin_id', 'app_id'], 'required'],
            [['plugin_id', 'status', 'pos'], 'integer'],
            [['trigger_class', 'trigger_event', 'handler_method'], 'string', 'max' => 255],
            [['data'], JsonValidator::class],
            //[['plugin_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::class, 'targetAttribute' => ['id']],
            //[['app_id'], 'exist', 'skipOnError' => true, 'targetClass' => App::class, 'targetAttribute' => ['id']],
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
            'trigger_class' => Yii::t('plugin', 'Trigger Class'),
            'trigger_event' => Yii::t('plugin', 'Trigger Event'),
            'handler_method' => Yii::t('plugin', 'Handler Method'),
            'data' => Yii::t('plugin', 'Data'),
            'pos' => Yii::t('plugin', 'Position'),
            'status' => Yii::t('plugin', 'Status'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getApp()
    {
        return $this->hasOne(App::class, ['id' => 'app_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlugin()
    {
        return $this->hasOne(Item::class, ['id' => 'plugin_id']);
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
     * eventList for BootstrapManager
     * @return array
     */
    public static function eventList($appId = 'frontend')
    {
        // (frontentd and common) or (backend and common) events
        $cond = ($appId == 'backend') ? '>=' : '<=';
        $attributes = ['trigger_class', 'trigger_event', 'plugin_id', 'pos', 'handler_method']; // handler_class
        $order = array_combine($attributes, array_fill(0, count($attributes), SORT_ASC));

        $allEvents = self::find()
            ->select('t.*')
            ->from(self::tableName() . 'AS t')
            ->joinWith(['plugin'])
            ->where([
                't.status' => self::STATUS_ACTIVE,
                Item::tableName() . '.status' => Item::STATUS_ACTIVE,
            ])
            ->andWhere([$cond, 't.app_id', App::APP_COMMON])
            ->orderBy($order)
            ->all();

        $result = [];

        foreach ($allEvents as $data) {
            if ($data->data) {
                $handler = [[$data->plugin->handler_class, $data->handler_method], json_decode($data->data, true)];
            } else {
                $handler = [$data->plugin->handler_class, $data->handler_method];
            }
            $result[$data->trigger_class][$data->trigger_event][] = $handler;
        }

        return $result;
    }
}
