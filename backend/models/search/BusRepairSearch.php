<?php

namespace backend\models\search;

use common\models\BusRepair;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BusRepairSearch represents the model behind the search form of `common\models\BusRepair`.
 */
class BusRepairSearch extends BusRepair
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'phone', 'repair_card', 'reason', 'remark', 'feedback_msg'], 'safe'],
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
        $query = BusRepair::find()->orderBy('id desc');

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
            'status' => $this->status
        ]);
        if ($this->create_time) {
            $query->andFilterWhere(['between', 'create_time', strtotime($this->create_time), strtotime($this->create_time) + 24 * 3600 - 1]);
        }
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'repair_card', $this->repair_card])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'feedback_msg', $this->feedback_msg])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }
}
