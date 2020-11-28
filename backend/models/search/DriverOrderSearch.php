<?php

namespace backend\models\search;

use common\models\DriverOrder;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DriverOrderSearch represents the model behind the search form of `common\models\DriverOrder`.
 */
class DriverOrderSearch extends DriverOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_type', 'order_id', 'driver_id', 'package_num','customer_id', 'bus_id',  'create_time', 'update_time'], 'integer'],
            [['order_no', 'title', 'bus_card', 'start_date','status'], 'safe'],
            [['commission'], 'number'],
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
        $query = DriverOrder::find();

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
            'order_type' => $this->order_type,
            'order_id' => $this->order_id,
            'customer_id' => $this->customer_id,
            'package_num' => $this->package_num,
            'bus_id' => $this->bus_id,
            'start_date' => $this->start_date,
            'commission' => $this->commission,
            'status' => $this->status,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'bus_card', $this->bus_card])
            ->andFilterWhere(['like', 'driver_id', $this->driver_id]);

        return $dataProvider;
    }
}
