<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\PaymentGatewayQueue;

/**
 * PaymentGatewayQueueSearch represents the model behind the search form of `common\models\PaymentGatewayQueue`.
 */
class PaymentGatewayQueueSearch extends PaymentGatewayQueue
{


    public $store_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_gateway_queue_id', 'queue_status'], 'integer'],
            [['restaurant_uuid', 'store_name','payment_gateway', 'queue_created_at', 'queue_updated_at', 'queue_start_at', 'queue_end_at'], 'safe'],
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
        $query = PaymentGatewayQueue::find()
        ->joinWith(['restaurant'])->orderBy(['queue_status' => SORT_ASC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        $dataProvider->sort->attributes['store_name'] = [
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
            'payment_gateway_queue_id' => $this->payment_gateway_queue_id,
            'queue_status' => $this->queue_status,
            'queue_created_at' => $this->queue_created_at,
            'queue_updated_at' => $this->queue_updated_at,
            'queue_start_at' => $this->queue_start_at,
            'queue_end_at' => $this->queue_end_at,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'restaurant.name', $this->store_name])
            ->andFilterWhere(['like', 'payment_gateway', $this->payment_gateway]);

        return $dataProvider;
    }
}
