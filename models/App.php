<?php

namespace lo\plugins\models;

use Yii;

/**
 * This is the model class for table "{{%plugins__app}}".
 *
 * @property integer $id
 * @property string $name
 */
class App extends \yii\db\ActiveRecord
{

    const APP_FRONTEND = 1;
    const APP_COMMON = 2;
    const APP_BACKEND = 3;
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
        return $this->hasMany(Event::className(), ['app_id' => 'id']);
    }

}
