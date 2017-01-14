<?php

namespace lo\plugins\models\search;

use lo\plugins\models\Shortcode;
use yii\data\ActiveDataProvider;

/**
 * Class ShortcodeSearch
 * @package lo\plugins\models\search
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ShortcodeSearch extends Shortcode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'plugin_id', 'category_id', 'app_id', 'status'], 'integer'],
            [['handler_class', 'data', 'tag', 'tooltip'], 'safe'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Shortcode::find()->with('category');

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
            'category_id' => $this->category_id,
            'status' => $this->status
        ]);

        $query->andFilterWhere(['like', 'handler_class', $this->handler_class])
            ->andFilterWhere(['like', 'tag', $this->tag])
            ->andFilterWhere(['like', 'tooltip', $this->tooltip])
            ->andFilterWhere(['like', 'data', $this->data]);

        return $dataProvider;
    }
}
