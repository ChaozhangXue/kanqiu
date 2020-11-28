<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StationAllocation;

/**
 * StationAllocationSearch represents the model behind the search form of `common\models\StationAllocation`.
 */
class StationAllocationSearch extends StationAllocation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bus_id', 'line_id', 'service_id', 'create_time', 'update_time'], 'integer'],
            [['line_name', 'service_name', 'in_charge_name', 'telphone', 'build_time', 'create_people'], 'safe'],
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
        $query = StationAllocation::find();

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
            'bus_id' => $this->bus_id,
            'line_id' => $this->line_id,
            'service_id' => $this->service_id,
            'build_time' => $this->build_time,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'line_name', $this->line_name])
            ->andFilterWhere(['like', 'service_name', $this->service_name])
            ->andFilterWhere(['like', 'in_charge_name', $this->in_charge_name])
            ->andFilterWhere(['like', 'telphone', $this->telphone])
            ->andFilterWhere(['like', 'create_people', $this->create_people]);

        return $dataProvider;
    }
}
