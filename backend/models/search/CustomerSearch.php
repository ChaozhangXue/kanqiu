<?php

namespace backend\models\search;

use common\models\Customer;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CustomerSearch represents the model behind the search form of `backend\models\Customer`.
 */
class CustomerSearch extends Customer
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'type', 'gender', 'status', 'verify_status'], 'integer'],
            [['username', 'password', 'realname', 'mobile', 'nickname', 'birth_date'], 'safe'],
            [['create_time', 'last_login_time'], 'date']
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
        $query = Customer::find()->orderBy('customer_id desc');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        $query->andFilterWhere([
            'type' => $this->type,
        ]);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'customer_id' => $this->customer_id,
            'type' => $this->type,
            'gender' => $this->gender,
            'status' => $this->status,
            'verify_status' => $this->verify_status,
        ]);

        if ($this->birth_date != '') {
            if ($this->type == 1) {
                $query->andFilterWhere(['year(birth_date)' => date('Y', strtotime('-' . $this->birth_date . ' years'))]);
            } else {
                $query->andFilterWhere(['like', 'birth_date', $this->birth_date]);
            }
        }
        if ($this->create_time) {
            $query->andFilterWhere(['between', 'create_time', strtotime($this->create_time), strtotime($this->create_time) + 24 * 3600 - 1]);
        }
        if ($this->last_login_time) {
            $query->andFilterWhere(['between', 'last_login_time', strtotime($this->last_login_time), strtotime($this->last_login_time) + 24 * 3600 - 1]);
        }
        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'realname', $this->realname])
            ->andFilterWhere(['like', 'mobile', $this->mobile]);
        return $dataProvider;
    }
}
