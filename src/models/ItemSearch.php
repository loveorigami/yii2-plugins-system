<?php

namespace lo\plugins\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use lo\plugins\models\Item;

/**
 * ItemSearch represents the model behind the search form about `lo\plugins\models\Item`.
 */
class ItemSearch extends Item
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['handler_class', 'name', 'url', 'version', 'text', 'author', 'author_url'], 'safe'],
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
        $query = Item::find();

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
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'handler_class', $this->handler_class])
            ->andFilterWhere(['like', 'url', $this->url])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'author_url', $this->author_url]);

        return $dataProvider;
    }
}
