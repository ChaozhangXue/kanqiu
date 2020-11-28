<?php

namespace backend\models\search;

use common\models\DriverBill;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DriverBillSearch represents the model behind the search form of `common\models\DriverBill`.
 */
class DriverBillSearch extends DriverBill
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'bill_type', 'order_id', 'driver_id', 'customer_id', 'pay_method', 'package_num', 'status',], 'integer'],
            [['order_time', 'verify_time', 'pay_time', 'create_time', 'update_time'], 'date'],
            [['order_no', 'driver_name', 'transaction_id'], 'safe'],
            [['amount', 'commission'], 'number'],
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
        $query = DriverBill::find();

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
            'bill_type' => $this->bill_type,
            'order_id' => $this->order_id,
            'driver_id' => $this->driver_id,
            'customer_id' => $this->customer_id,
            'pay_method' => $this->pay_method,
            'amount' => $this->amount,
            'commission' => $this->commission,
            'package_num' => $this->package_num,
            'status' => $this->status,
        ]);
        if ($this->order_time) {
            $query->andFilterWhere(['between', 'order_time', strtotime($this->order_time), strtotime($this->order_time) + 24 * 3600 - 1]);
        }
        if ($this->verify_time) {
            $query->andFilterWhere(['between', 'verify_time', strtotime($this->verify_time), strtotime($this->verify_time) + 24 * 3600 - 1]);
        }
        if ($this->pay_time) {
            $query->andFilterWhere(['between', 'order_time', strtotime($this->pay_time), strtotime($this->pay_time) + 24 * 3600 - 1]);
        }
        if ($this->create_time) {
            $query->andFilterWhere(['between', 'order_time', strtotime($this->create_time), strtotime($this->create_time) + 24 * 3600 - 1]);
        }
        if ($this->update_time) {
            $query->andFilterWhere(['between', 'order_time', strtotime($this->update_time), strtotime($this->update_time) + 24 * 3600 - 1]);
        }
        $query->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'driver_name', $this->driver_name])
            ->andFilterWhere(['like', 'transaction_id', $this->transaction_id]);

        return $dataProvider;
    }
}
