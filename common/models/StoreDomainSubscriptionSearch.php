<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StoreDomainSubscription;

/**
 * StoreDomainSubscriptionSearch represents the model behind the search form of `common\models\StoreDomainSubscription`.
 */
class StoreDomainSubscriptionSearch extends StoreDomainSubscription
{
    public $restaurantName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [["restaurantName", 'subscription_uuid', 'restaurant_uuid', 'domain_registrar', 'domain', 'from', 'to', 'created_at', 'updated_at'], 'safe'],
            [['created_by', 'updated_by'], 'integer'],
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
        $query = StoreDomainSubscription::find()
            ->joinWith(['restaurant']);

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
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'from' => $this->from,
            'to' => $this->to,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'subscription_uuid', $this->subscription_uuid])
            ->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'domain_registrar', $this->domain_registrar])
            ->andFilterWhere(['like', 'domain', $this->domain])
            ->andFilterWhere(['like', 'restaurant.name', $this->restaurantName]);;

        return $dataProvider;
    }
}
