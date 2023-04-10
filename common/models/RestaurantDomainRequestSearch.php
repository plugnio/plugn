<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantDomainRequest;

/**
 * RestaurantDomainRequestSearch represents the model behind the search form of `common\models\RestaurantDomainRequest`.
 */
class RestaurantDomainRequestSearch extends RestaurantDomainRequest
{
    public $storeName;
    public $agentName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_uuid', 'domain', 'created_at', 'updated_at'], 'safe'],
            [['agentName','storeName','restaurant_uuid','status', 'created_by'], 'integer'],
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
        $query = RestaurantDomainRequest::find()
            ->joinWith('restaurant');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['agentName'] = [
            'asc' => ['agent.agent_name' => SORT_ASC],
            'desc' => ['agent.agent_name' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['storeName'] = [
            'asc' => ['restaurant.name' => SORT_ASC],
            'desc' => ['restaurant.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'status' => $this->status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'request_uuid', $this->request_uuid])
            ->andFilterWhere(['like', 'domain', $this->domain]);

        return $dataProvider;
    }
}
