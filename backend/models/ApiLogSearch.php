<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ApiLog;

/**
 * ApiLogSearch represents the model behind the search form of `common\models\ApiLog`.
 */
class ApiLogSearch extends ApiLog
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_uuid', 'restaurant_uuid', 'method', 'endpoint', 'request_headers', 'request_body', 'response_headers', 'response_body', 'created_at'], 'safe'],
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
        $query = ApiLog::find();

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
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'log_uuid', $this->log_uuid])
            ->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'method', $this->method])
            ->andFilterWhere(['like', 'endpoint', $this->endpoint])
            ->andFilterWhere(['like', 'request_headers', $this->request_headers])
            ->andFilterWhere(['like', 'request_body', $this->request_body])
            ->andFilterWhere(['like', 'response_headers', $this->response_headers])
            ->andFilterWhere(['like', 'response_body', $this->response_body]);

        return $dataProvider;
    }
}
