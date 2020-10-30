<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Subscription;

/**
 * SubscriptionSearch represents the model behind the search form of `common\models\Subscription`.
 */
class SubscriptionSearch extends Subscription
{


      // public restaurant_name;
      public $restaurant_name;
      public $plan_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['subscription_uuid', 'plan_id'], 'integer'],
            [['restaurant_uuid', 'subscription_start_at', 'subscription_end_at', 'restaurant_name', 'plan_name'], 'safe'],
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
        $query = Subscription::find()->joinWith(['plan','restaurant']);;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


          $dataProvider->sort->attributes['restaurant_name'] = [
              'asc' => ['restaurant.name' => SORT_ASC],
              'desc' => ['restaurant.name' => SORT_DESC],
          ];


          $dataProvider->sort->attributes['plan_name'] = [
              'asc' => ['plan.name' => SORT_ASC],
              'desc' => ['plan.name' => SORT_DESC],
          ];



        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'subscription_start_at' => $this->subscription_start_at,
            'subscription_end_at' => $this->subscription_end_at,
        ]);

        $query->andFilterWhere(['like', 'restaurant.name', $this->restaurant_name]);
        $query->andFilterWhere(['like', 'plan.name', $this->plan_name]);

        return $dataProvider;
    }
}
