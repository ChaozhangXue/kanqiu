<?php

namespace backend\models\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ServiceStation;

/**
 * ServiceStationSearch represents the model behind the search form of `common\models\ServiceStation`.
 */
class ServiceStationSearch extends ServiceStation
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'people_num', 'create_time', 'update_time'], 'integer'],
            [['country', 'station_name', 'address', 'entity', 'in_charge_name', 'id_card', 'build_size', 'code', 'telephone', 'build_time', 'service_time', 'backup'], 'safe'],
            [['longitude', 'latitude'], 'number'],
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
        $query = ServiceStation::find()->orderBy('id desc');

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
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'people_num' => $this->people_num,
            'build_time' => $this->build_time,
            'create_time' => $this->create_time,
            'update_time' => $this->update_time,
        ]);

        $query->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'station_name', $this->station_name])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'entity', $this->entity])
            ->andFilterWhere(['like', 'in_charge_name', $this->in_charge_name])
            ->andFilterWhere(['like', 'id_card', $this->id_card])
            ->andFilterWhere(['like', 'build_size', $this->build_size])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['like', 'service_time', $this->service_time])
            ->andFilterWhere(['like', 'backup', $this->backup]);

        return $dataProvider;
    }
}
