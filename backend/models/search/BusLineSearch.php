<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\BusLine;

/**
 * BusLineSearch represents the model behind the search form of `common\models\BusLine`.
 */
class BusLineSearch extends BusLine
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'station_num', 'type'], 'integer'],
            [['station_name', 'start_time', 'end_time', 'start_point', 'end_point', 'create_people', 'area', 'station_list', 'created_at', 'updated_at'], 'safe'],
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
        $query = BusLine::find();

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
            'station_num' => $this->station_num,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'station_name', $this->station_name])
            ->andFilterWhere(['like', 'start_time', $this->start_time])
            ->andFilterWhere(['like', 'end_time', $this->end_time])
            ->andFilterWhere(['like', 'start_point', $this->start_point])
            ->andFilterWhere(['like', 'end_point', $this->end_point])
            ->andFilterWhere(['like', 'create_people', $this->create_people])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'station_list', $this->station_list]);

        return $dataProvider;
    }
}
