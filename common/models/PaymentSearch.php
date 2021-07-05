<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Payment;

/**
 * PaymentSearch represents the model behind the search form of `common\models\Payment`.
 */
class PaymentSearch extends Payment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_uuid', 'restaurant_uuid','order_uuid', 'payment_gateway_order_id', 'payment_gateway_transaction_id', 'payment_mode', 'payment_current_status', 'payment_udf1', 'payment_udf2', 'payment_udf3', 'payment_udf4', 'payment_udf5', 'payment_created_at', 'payment_updated_at'], 'safe'],
            [['customer_id'], 'integer'],
            [['payment_amount_charged', 'payment_net_amount', 'payment_gateway_fee'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return parent::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $restaurantUuid)
    {
        $query = Payment::find()->where(['restaurant_uuid' => $restaurantUuid]);

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
            'customer_id' => $this->customer_id,
            'payment_amount_charged' => $this->payment_amount_charged,
            'payment_net_amount' => $this->payment_net_amount,
            'payment_gateway_fee' => $this->payment_gateway_fee,
            'payment_created_at' => $this->payment_created_at,
            'payment_updated_at' => $this->payment_updated_at,
        ]);

        $query->andFilterWhere(['like', 'payment_uuid', $this->payment_uuid])
            ->andFilterWhere(['like', 'order_uuid', $this->order_uuid])
            ->andFilterWhere(['like', 'payment_gateway_order_id', $this->payment_gateway_order_id])
            ->andFilterWhere(['like', 'payment_gateway_transaction_id', $this->payment_gateway_transaction_id])
            ->andFilterWhere(['like', 'payment_mode', $this->payment_mode])
            ->andFilterWhere(['like', 'payment_current_status', $this->payment_current_status])
            ->andFilterWhere(['like', 'payment_udf1', $this->payment_udf1])
            ->andFilterWhere(['like', 'payment_udf2', $this->payment_udf2])
            ->andFilterWhere(['like', 'payment_udf3', $this->payment_udf3])
            ->andFilterWhere(['like', 'payment_udf4', $this->payment_udf4])
            ->andFilterWhere(['like', 'payment_udf5', $this->payment_udf5]);

        return $dataProvider;
    }
}
