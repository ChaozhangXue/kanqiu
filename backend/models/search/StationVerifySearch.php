<?php

namespace backend\models\search;

use common\models\StationVerify;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CustomerVerifySearch represents the model behind the search form of `common\models\CustomerVerify`.
 */
class StationVerifySearch extends StationVerify
{
    public $station_name;
    public $country;
    public $in_charge_name;
    public $people_num;
    public $telephone;
    public $build_time;
    public $backup;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['verify_id', 'customer_id', 'verify_status'], 'integer'],
            [['station_name', 'country', 'in_charge_name', 'people_num', 'telephone', 'backup'], 'string'],
            [['build_time', 'create_time'], 'date'],
            [['front_photo', 'back_photo', 'realname', 'idcard'], 'safe'],
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
        $query = StationVerify::find()->orderBy('verify_id desc');
        $query->joinWith(['station']);


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
            'verify_id' => $this->verify_id,
            'customer_id' => $this->customer_id,
            'station_id' => $this->station_id,
            'verify_status' => $this->verify_status,
        ]);
        if ($this->create_time) {
            $query->andFilterWhere(['between', 'create_time', strtotime($this->create_time), strtotime($this->create_time) + 24 * 3600 - 1]);
        }
        if ($this->build_time) {
            $query->andFilterWhere(['between', 'service_station.build_time', strtotime($this->build_time), strtotime($this->build_time) + 24 * 3600 - 1]);
        }
        $query->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'service_station.station_name', $this->station_name])
            ->andFilterWhere(['like', 'service_station.country', $this->country])
            ->andFilterWhere(['like', 'service_station.telephone', $this->telephone])
            ->andFilterWhere(['like', 'service_station.in_charge_name', $this->in_charge_name])
            ->andFilterWhere(['like', 'service_station.people_num', $this->people_num])
            ->andFilterWhere(['like', 'service_station.backup', $this->backup])
            ->andFilterWhere(['like', 'idcard', $this->idcard]);

        return $dataProvider;
    }
}
