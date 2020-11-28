<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Bus;

/**
 * BusSearch represents the model behind the search form of `common\models\Bus`.
 */
class BusSearch extends Bus
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'num', 'car_type'], 'integer'],
            [['brand', 'model', 'card', 'color', 'buy_time', 'dept', 'created_at', 'updated_at'], 'safe'],
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
        $query = Bus::find();

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
            'num' => $this->num,
            'car_type' => $this->car_type,
            'buy_time' => $this->buy_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'card', $this->card])
            ->andFilterWhere(['like', 'color', $this->color])
            ->andFilterWhere(['like', 'dept', $this->dept]);

        return $dataProvider;
    }
}
