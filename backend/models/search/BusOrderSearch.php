<?php

namespace backend\models\search;

use common\models\BusOrder;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BusOrderSearch represents the model behind the search form of `common\models\BusOrder`.
 */
class BusOrderSearch extends BusOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'driver_id', 'add_type', 'car_type', 'order_type', 'user_number', 'seat_type', 'pay_method', 'status', 'customer_id'], 'integer'],
            [['order_no', 'title', 'mobile', 'use_people', 'reason', 'driver_name',
                'driver_phone', 'dispatch_start', 'dispatch_end', 'remark', 'transaction_id'], 'safe'],
            [['money', 'pay_money','commission'], 'number'],
            [['pay_time', 'start_time', 'end_time', 'create_time', 'update_time'], 'date']
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
        $query = BusOrder::find()->orderBy('id desc');

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
            'customer_id' => $this->customer_id,
            'money' => $this->money,
            'pay_money' => $this->pay_money,
            'commission' => $this->commission,
            'add_type' => $this->add_type,
            'user_number' => $this->user_number,
            'car_type' => $this->car_type,
            'order_type' => $this->order_type,
            'seat_type' => $this->seat_type,
            'pay_method' => $this->pay_method,
            'driver_id' => $this->driver_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'use_people', $this->use_people])
            ->andFilterWhere(['like', 'driver_name', $this->driver_name])
            ->andFilterWhere(['like', 'driver_phone', $this->driver_phone])
            ->andFilterWhere(['like', 'dispatch_start', $this->dispatch_start])
            ->andFilterWhere(['like', 'dispatch_end', $this->dispatch_end])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'transaction_id', $this->transaction_id])
            ->andFilterWhere(['like', 'reason', $this->reason]);

        return $dataProvider;
    }
}
