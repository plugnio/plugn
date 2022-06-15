<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Refund;

/**
 * RefundSearch represents the model behind the search form of `common\models\Refund`.
 */
class RefundSearch extends Refund
{
    public $store_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['refund_id', 'store_name', 'payment_uuid', 'restaurant_uuid', 'order_uuid', 'reason', 'refund_status', 'refund_created_at', 'refund_updated_at', 'refund_reference', 'refund_message'], 'safe'],
            [['refund_amount'], 'number'],
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
        $query = Refund::find()->with('store')
                        ->orderBy(['refund_created_at' => SORT_DESC]);

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

        $dataProvider->sort->attributes['store_name'] = [
            'asc' => ['store.name' => SORT_ASC],
            'desc' => ['store.name' => SORT_DESC],
        ];


        // grid filtering conditions
        $query->andFilterWhere([
            'refund_amount' => $this->refund_amount,
            'refund_created_at' => $this->refund_created_at,
            'refund_updated_at' => $this->refund_updated_at,
        ]);

        $query->andFilterWhere(['like', 'refund_id', $this->refund_id])
            ->andFilterWhere(['like', 'payment_uuid', $this->payment_uuid])
            ->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'order_uuid', $this->order_uuid])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'refund_status', $this->refund_status])
            ->andFilterWhere(['like', 'refund_reference', $this->refund_reference]);

        return $dataProvider;
    }
}
