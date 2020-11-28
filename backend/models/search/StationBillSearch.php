<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StationBill;

/**
 * StationBillSearch represents the model behind the search form of `common\models\StationBill`.
 */
class StationBillSearch extends StationBill
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'order_id', 'station_id', 'order_type', 'package_num', 'status'], 'integer'],
            [['station_name', 'owner', 'time', 'verify_time', 'bill_time'], 'safe'],
            [['money', 'yongjin'], 'number'],
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
        $query = StationBill::find();

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
            'order_id' => $this->order_id,
            'station_id' => $this->station_id,
            'order_type' => $this->order_type,
            'time' => $this->time,
            'money' => $this->money,
            'yongjin' => $this->yongjin,
            'package_num' => $this->package_num,
            'verify_time' => $this->verify_time,
            'status' => $this->status,
            'bill_time' => $this->bill_time,
        ]);

        $query->andFilterWhere(['like', 'station_name', $this->station_name])
            ->andFilterWhere(['like', 'owner', $this->owner]);

        return $dataProvider;
    }
}
