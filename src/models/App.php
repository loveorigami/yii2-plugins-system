<?php

namespace lo\plugins\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%plugins__app}}".
 *
 * @property integer $id
 * @property string $name
 */
class App extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plugins__app}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('plugin', 'ID'),
            'name' => Yii::t('plugin', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::class, ['app_id' => 'id']);
    }

}
