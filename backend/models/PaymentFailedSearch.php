<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PaymentFailed;

/**
 * PaymentFailedSearch represents the model behind the search form of `common\models\PaymentFailed`.
 */
class PaymentFailedSearch extends PaymentFailed
{
    public $restaurantUuid;
    public  $restaurantName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_failed_uuid', 'restaurantUuid','restaurantName', 'restaurant_uuid', 'payment_uuid', 'order_uuid', 'response', 'created_at', 'updated_at'], 'safe'],
            [['customer_id'], 'integer'],
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

    public function getRestaurantName() {
        return $this->restaurant ? $this->restaurant->name: null;
    }

    public function getRestaurantUuid() {
        return $this->order? $this->order->restaurant_uuid: null;
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
        $query = PaymentFailed::find()
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
            'customer_id' => $this->customer_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ]);

        if($this->restaurantUuid) {
            $query->joinWith(['order'])
                ->andWhere(['order.restaurant_uuid' => $this->restaurantUuid]);
        }

        if($this->restaurantName) {
            $query->andWhere(['like', 'restaurant.name', $this->restaurantName]);
        }

        $query->andFilterWhere(['like', 'payment_failed_uuid', $this->payment_failed_uuid])
            ->andFilterWhere(['like', 'payment_uuid', $this->payment_uuid])
            ->andFilterWhere(['like', 'order_uuid', $this->order_uuid])
            ->andFilterWhere(['like', 'response', $this->response]);

        return $dataProvider;
    }
}
