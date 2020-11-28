<?php

namespace backend\models\search;

use common\models\CustomerVerify;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CustomerVerifySearch represents the model behind the search form of `common\models\CustomerVerify`.
 */
class CustomerVerifySearch extends CustomerVerify
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['verify_id', 'customer_id', 'verify_status', 'gender'], 'integer'],
            [['front_photo', 'back_photo', 'realname', 'idcard', 'mobile'], 'safe'],
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
        $query = CustomerVerify::find()->orderBy('verify_id desc');

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
            'verify_status' => $this->verify_status,
            'gender' => $this->gender,
            'mobile' => $this->mobile,
        ]);
        if ($this->create_time) {
            $query->andFilterWhere(['between', 'create_time', strtotime($this->create_time), strtotime($this->create_time) + 24 * 3600 - 1]);
        }
        $query->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'idcard', $this->idcard]);

        return $dataProvider;
    }
}
