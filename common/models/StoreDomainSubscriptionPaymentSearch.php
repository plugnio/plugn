<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StoreDomainSubscriptionPayment;

/**
 * StoreDomainSubscriptionPaymentSearch represents the model behind the search form of `common\models\StoreDomainSubscriptionPayment`.
 */
class StoreDomainSubscriptionPaymentSearch extends StoreDomainSubscriptionPayment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['store_domain_subscription_payment_uuid', 'subscription_uuid', 'from', 'to', 'created_at', 'updated_at'], 'safe'],
            [['total_amount', 'cost_amount'], 'number'],
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
        $query = StoreDomainSubscriptionPayment::find();

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
            'from' => $this->from,
            'to' => $this->to,
            'total_amount' => $this->total_amount,
            'cost_amount' => $this->cost_amount,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'store_domain_subscription_payment_uuid', $this->store_domain_subscription_payment_uuid])
            ->andFilterWhere(['like', 'subscription_uuid', $this->subscription_uuid]);

        return $dataProvider;
    }
}
