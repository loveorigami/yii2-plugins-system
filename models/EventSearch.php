<?php

namespace lo\plugins\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use lo\plugins\models\Event;

/**
 * EventSearch represents the model behind the search form about `lo\plugins\models\Event`.
 */
class EventSearch extends Event
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'plugin_id', 'app_id', 'status'], 'integer'],
            [['trigger_class', 'trigger_event', 'handler_method'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Event::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'plugin_id' => $this->plugin_id,
            'app_id' => $this->app_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'trigger_class', $this->trigger_class])
            ->andFilterWhere(['like', 'trigger_event', $this->trigger_event])
            ->andFilterWhere(['like', 'handler_method', $this->handler_method]);

        return $dataProvider;
    }
}
