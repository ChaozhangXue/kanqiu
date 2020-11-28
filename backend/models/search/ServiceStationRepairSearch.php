<?php

namespace backend\models\search;

use common\models\BusStation;
use common\models\ServiceStationRepair;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ServiceStationRepairSearch represents the model behind the search form of `common\models\ServiceStationRepair`.
 */
class ServiceStationRepairSearch extends ServiceStationRepair
{
    public $repair_type;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'repair_type'], 'integer'],
            [['name', 'phone', 'reason', 'remark', 'feedback_msg'], 'safe'],
            [['create_time'], 'date']
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
        $query = ServiceStationRepair::find()->orderBy('id desc');

        $query->joinWith(['customer']);
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
            'status' => $this->status,
            'customer.type' => $this->repair_type,
        ]);
        if ($this->create_time) {
            $query->andFilterWhere(['between', 'create_time', strtotime($this->create_time), strtotime($this->create_time) + 24 * 3600 - 1]);
        }
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
