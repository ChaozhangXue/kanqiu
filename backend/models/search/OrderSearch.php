<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'receive_station_id', 'send_station_id', 'driver_id', 'driver_accept_type', 'bus_id', 'package_num', 'source', 'status', 'type'], 'integer'],
            [['package_id_list', 'receive_station_name', 'send_station_name', 'driver_name', 'driver_phone', 'bus_line', 'bus_time', 'card', 'deliver_time', 'station_time', 'receiver_time', 'detail', 'created_at', 'updated_at'], 'safe'],
            [['total_account', 'yongjin'], 'number'],
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
        $query = Order::find()->orderBy('id desc');

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
            'receive_station_id' => $this->receive_station_id,
            'send_station_id' => $this->send_station_id,
            'driver_id' => $this->driver_id,
            'driver_accept_type' => $this->driver_accept_type,
            'bus_id' => $this->bus_id,
            'deliver_time' => $this->deliver_time,
            'station_time' => $this->station_time,
            'receiver_time' => $this->receiver_time,
            'package_num' => $this->package_num,
            'source' => $this->source,
            'total_account' => $this->total_account,
            'yongjin' => $this->yongjin,
            'status' => $this->status,
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'package_id_list', $this->package_id_list])
            ->andFilterWhere(['like', 'receive_station_name', $this->receive_station_name])
            ->andFilterWhere(['like', 'send_station_name', $this->send_station_name])
            ->andFilterWhere(['like', 'driver_name', $this->driver_name])
            ->andFilterWhere(['like', 'driver_phone', $this->driver_phone])
            ->andFilterWhere(['like', 'bus_line', $this->bus_line])
            ->andFilterWhere(['like', 'bus_time', $this->bus_time])
            ->andFilterWhere(['like', 'card', $this->card])
            ->andFilterWhere(['like', 'detail', $this->detail]);

        return $dataProvider;
    }
}
