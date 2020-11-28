<?php

namespace backend\models\search;

use common\models\Driver;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DriverSearch represents the model behind the search form of `common\models\Driver`.
 */
class DriverSearch extends Driver
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'gender', 'type', 'status'], 'integer'],
            [['realname', 'birth_date', 'dept', 'job_position', 'employment_time', 'license', 'mobile', 'idcard'], 'safe'],
            [['create_time', 'update_time', 'entry_time'], 'date']
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
        $query = Driver::find()->orderBy('id desc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        $query->andFilterWhere([
            'type' => $this->type,
            'status' => $this->status
        ]);
        if (!$this->validate()) {

            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gender' => $this->gender,
        ]);
        if ($this->birth_date) {
            $start = (date('Y', time()) - $this->birth_date) . '-01-01';
            $end = (date('Y', time()) - $this->birth_date) . '-12-31';
            $query->andFilterWhere(['between', 'birth_date', $start, $end]);
        }
        if ($this->create_time) {
            $query->andFilterWhere(['between', 'create_time', strtotime($this->create_time), strtotime($this->create_time) + 24 * 3600 - 1]);
        }
        if ($this->entry_time) {
            $query->andFilterWhere(['between', 'entry_time', strtotime($this->entry_time), strtotime($this->entry_time) + 24 * 3600 - 1]);
        }
        $query->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'dept', $this->dept])
            ->andFilterWhere(['like', 'job_position', $this->job_position])
            ->andFilterWhere(['like', 'employment_time', $this->employment_time])
            ->andFilterWhere(['like', 'license', $this->license])
            ->andFilterWhere(['like', 'mobile', $this->mobile])
            ->andFilterWhere(['like', 'idcard', $this->idcard]);

        return $dataProvider;
    }
}
