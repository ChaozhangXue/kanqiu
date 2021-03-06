<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Motor;

/**
 * MotorSearch represents the model behind the search form of `common\models\Motor`.
 */
class MotorSearch extends Motor
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'site_num', 'num', 'car_type'], 'integer'],
            [['brand', 'model', 'card', 'color', 'buy_time', 'created_at', 'updated_at'], 'safe'],
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
        $query = Motor::find();

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
            'site_num' => $this->site_num,
            'num' => $this->num,
            'car_type' => $this->car_type,
            'buy_time' => $this->buy_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'model', $this->model])
            ->andFilterWhere(['like', 'card', $this->card])
            ->andFilterWhere(['like', 'color', $this->color]);

        return $dataProvider;
    }
}
