<?php

namespace backend\models\search;

use common\models\SystemMsg;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SystemMsgSearch represents the model behind the search form of `common\models\SystemMsg`.
 */
class SystemMsgSearch extends SystemMsg
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status', 'customer_type'], 'integer'],
            [['title', 'content', 'publisher', 'receive_id'], 'safe'],
            [['create_time', 'publish_time'], 'date']
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
        $query = SystemMsg::find()->orderBy('id desc');

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
            'type' => $this->type,
            'customer_type' => $this->customer_type,
        ]);

        if ($this->status !== '') {
            $query->andFilterWhere(['status' => $this->status]);
        } else {
            $query->andFilterWhere(['!=', 'status', 0]);
        }
        if ($this->create_time) {
            $query->andFilterWhere(['between', 'create_time', strtotime($this->create_time), strtotime($this->create_time) + 24 * 3600 - 1]);
        }
        if ($this->publish_time) {
            $query->andFilterWhere(['between', 'publish_time', strtotime($this->publish_time), strtotime($this->publish_time) + 24 * 3600 - 1]);
        }
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'publisher', $this->publisher])
            ->andFilterWhere(['like', 'receive_id', $this->receive_id]);

        return $dataProvider;
    }
}
