<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use common\models\SubscriptionPayment;

/**
 * SubscriptionPaymentSearch represents the model behind the search form of `common\models\SubscriptionPayment`.
 */
class SubscriptionPaymentSearch extends SubscriptionPayment
{

  public $store_name;



    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_uuid', 'restaurant_uuid', 'subscription_uuid', 'payment_gateway_order_id', 'payment_gateway_transaction_id', 'payment_mode', 'payment_current_status', 'payment_udf1', 'payment_udf2', 'payment_udf3', 'payment_udf4', 'payment_udf5', 'payment_created_at', 'payment_updated_at', 'response_message', 'payment_token', 'partner_payout_uuid','store_name'], 'safe'],
            [['payment_amount_charged', 'payment_net_amount', 'payment_gateway_fee', 'partner_fee'], 'number'],
            [['received_callback', 'payout_status'], 'integer'],
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

        $query = SubscriptionPayment::find()
                        ->joinWith(['restaurant'])
                        ->orderBy([
                          'payment_current_status' => SORT_ASC
                        ]);

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
            'payment_amount_charged' => $this->payment_amount_charged,
            'payment_net_amount' => $this->payment_net_amount,
            'payment_gateway_fee' => $this->payment_gateway_fee,
            'payment_created_at' => $this->payment_created_at,
            'payment_updated_at' => $this->payment_updated_at,
            'received_callback' => $this->received_callback,
            'partner_fee' => $this->partner_fee,
            'payout_status' => $this->payout_status,
        ]);

        $query->andFilterWhere(['like', 'payment_uuid', $this->payment_uuid])
            ->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'restaurant.name', $this->store_name])
            ->andFilterWhere(['like', 'subscription_uuid', $this->subscription_uuid])
            ->andFilterWhere(['like', 'payment_gateway_order_id', $this->payment_gateway_order_id])
            ->andFilterWhere(['like', 'payment_gateway_transaction_id', $this->payment_gateway_transaction_id])
            ->andFilterWhere(['like', 'payment_mode', $this->payment_mode])
            ->andFilterWhere(['like', 'payment_current_status', $this->payment_current_status])
            ->andFilterWhere(['like', 'payment_udf1', $this->payment_udf1])
            ->andFilterWhere(['like', 'payment_udf2', $this->payment_udf2])
            ->andFilterWhere(['like', 'payment_udf3', $this->payment_udf3])
            ->andFilterWhere(['like', 'payment_udf4', $this->payment_udf4])
            ->andFilterWhere(['like', 'payment_udf5', $this->payment_udf5])
            ->andFilterWhere(['like', 'response_message', $this->response_message])
            ->andFilterWhere(['like', 'payment_token', $this->payment_token])
            ->andFilterWhere(['like', 'partner_payout_uuid', $this->partner_payout_uuid]);

        return $dataProvider;
    }
}
