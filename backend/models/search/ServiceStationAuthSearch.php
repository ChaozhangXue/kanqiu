<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ServiceStationAuth;

/**
 * ServiceStationAuthSearch represents the model behind the search form of `common\models\ServiceStationAuth`.
 */
class ServiceStationAuthSearch extends ServiceStationAuth
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'people_num'], 'integer'],
            [['country', 'station_name', 'in_charge_name', 'telphone', 'build_time', 'service_time', 'backup', 'is_authed', 'front_pic', 'back_pic', 'created_at', 'updated_at'], 'safe'],
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
        $query = ServiceStationAuth::find();

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
            'people_num' => $this->people_num,
            'build_time' => $this->build_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'station_name', $this->station_name])
            ->andFilterWhere(['like', 'in_charge_name', $this->in_charge_name])
            ->andFilterWhere(['like', 'telphone', $this->telphone])
            ->andFilterWhere(['like', 'service_time', $this->service_time])
            ->andFilterWhere(['like', 'backup', $this->backup])
            ->andFilterWhere(['like', 'is_authed', $this->is_authed])
            ->andFilterWhere(['like', 'front_pic', $this->front_pic])
            ->andFilterWhere(['like', 'back_pic', $this->back_pic]);

        return $dataProvider;
    }
}
