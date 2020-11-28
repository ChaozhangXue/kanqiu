<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PackageOrder;

/**
 * PackageOrderSearch represents the model behind the search form of `common\models\PackageOrder`.
 */
class PackageOrderSearch extends PackageOrder
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'package_list_id', 'station_check', 'customer_id', 'weight', 'size', 'distance', 'is_on_door', 'source', 'status', 'submit_station_id', 'receive_station_id'], 'integer'],
            [['delivery_num', 'pay_order_no', 'order_num', 'sender', 'sender_phone', 'send_address', 'sender_point', 'receiver', 'receiver_phone', 'receive_address', 'receive_point', 'type', 'express_company', 'sender_backup', 'deliver_time', 'station_time', 'receiver_time', 'submit_station_name', 'submit_station_phone', 'receive_station_name', 'receive_station_phone', 'created_at', 'updated_at'], 'safe'],
            [['total_account'], 'number'],
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
        $query = PackageOrder::find()->orderBy('id desc');

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
            'package_list_id' => $this->package_list_id,
            'station_check' => $this->station_check,
            'customer_id' => $this->customer_id,
            'weight' => $this->weight,
            'size' => $this->size,
            'distance' => $this->distance,
            'is_on_door' => $this->is_on_door,
            'deliver_time' => $this->deliver_time,
            'station_time' => $this->station_time,
            'receiver_time' => $this->receiver_time,
            'source' => $this->source,
            'total_account' => $this->total_account,
            'status' => $this->status,
            'submit_station_id' => $this->submit_station_id,
            'receive_station_id' => $this->receive_station_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'delivery_num', $this->delivery_num])
            ->andFilterWhere(['like', 'pay_order_no', $this->pay_order_no])
            ->andFilterWhere(['like', 'order_num', $this->order_num])
            ->andFilterWhere(['like', 'sender', $this->sender])
            ->andFilterWhere(['like', 'sender_phone', $this->sender_phone])
            ->andFilterWhere(['like', 'send_address', $this->send_address])
            ->andFilterWhere(['like', 'sender_point', $this->sender_point])
            ->andFilterWhere(['like', 'receiver', $this->receiver])
            ->andFilterWhere(['like', 'receiver_phone', $this->receiver_phone])
            ->andFilterWhere(['like', 'receive_address', $this->receive_address])
            ->andFilterWhere(['like', 'receive_point', $this->receive_point])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'express_company', $this->express_company])
            ->andFilterWhere(['like', 'sender_backup', $this->sender_backup])
            ->andFilterWhere(['like', 'submit_station_name', $this->submit_station_name])
            ->andFilterWhere(['like', 'submit_station_phone', $this->submit_station_phone])
            ->andFilterWhere(['like', 'receive_station_name', $this->receive_station_name])
            ->andFilterWhere(['like', 'receive_station_phone', $this->receive_station_phone]);

        return $dataProvider;
    }
}
