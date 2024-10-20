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
    public $restaurantName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [["restaurantName", 'log_uuid', 'restaurant_uuid', 'method', 'endpoint', 'request_headers', 'request_body', 'response_headers', 'response_body', 'created_at'], 'safe'],
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
        $query = ApiLog::find()
            ->joinWith(['restaurant'])
            ->orderBy("created_at DESC");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['restaurantName'] = [
            'asc' => ['restaurant.name' => SORT_ASC],
            'desc' => ['restaurant.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');x`
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'api_log.created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'api_log.log_uuid', $this->log_uuid])
            ->andFilterWhere(['like', 'api_log.restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'api_log.method', $this->method])
            ->andFilterWhere(['like', 'api_log.endpoint', $this->endpoint])
            ->andFilterWhere(['like', 'api_log.request_headers', $this->request_headers])
            ->andFilterWhere(['like', 'api_log.request_body', $this->request_body])
            ->andFilterWhere(['like', 'api_log.response_headers', $this->response_headers])
            ->andFilterWhere(['like', 'api_log.response_body', $this->response_body])
            ->andFilterWhere(['like', "restaurant.name", $this->restaurantName]);

        return $dataProvider;
    }
}
