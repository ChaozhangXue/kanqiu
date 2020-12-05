<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WatchList;

/**
 * WatchListSearch represents the model behind the search form of `common\models\WatchList`.
 */
class WatchListSearch extends WatchList
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'round'], 'integer'],
            [['game_date', 'game_time', 'team1', 'team2', 'game_link', 'create_time', 'update_time', 'expire_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = WatchList::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'game_date' => $this->game_date,
            'game_time' => $this->game_time,
            'round' => $this->round,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
            'expire_time' => $this->expire_time,
        ]);

        $query->andFilterWhere(['like', 'team1', $this->team1])
            ->andFilterWhere(['like', 'team2', $this->team2])
            ->andFilterWhere(['like', 'game_link', $this->game_link]);

        return $dataProvider;
    }
}
