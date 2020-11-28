<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ServiceStationAccount;

/**
 * ServiceStationAccountSearch represents the model behind the search form of `common\models\ServiceStationAccount`.
 */
class ServiceStationAccountSearch extends ServiceStationAccount
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'package_num', 'status', 'create_time', 'update_time'], 'integer'],
            [['order_num', 'station_name', 'in_charge_name', 'order_time', 'verify_time', 'bill_time'], 'safe'],
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
        $query = ServiceStationAccount::find();

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
            'order_time' => $this->order_time,
            'total_account' => $this->total_account,
            'yongjin' => $this->yongjin,
            'package_num' => $this->package_num,
            'verify_time' => $this->verify_time,
            'status' => $this->status,
            'bill_time' => $this->bill_time,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'order_num', $this->order_num])
            ->andFilterWhere(['like', 'station_name', $this->station_name])
            ->andFilterWhere(['like', 'in_charge_name', $this->in_charge_name]);

        return $dataProvider;
    }
}
