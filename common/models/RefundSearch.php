<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Refund;

/**
 * RefundSearch represents the model behind the search form of `common\models\Refund`.
 */
class RefundSearch extends Refund
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['refund_id'], 'integer'],
            [['restaurant_uuid', 'order_uuid', 'reason','refund_status'], 'safe'],
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
        $query = Refund::find();

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
            'refund_id' => $this->refund_id,
            'refund_amount' => $this->refund_amount,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'order_uuid', $this->order_uuid])
            ->andFilterWhere(['like', 'reason', $this->reason])
            ->andFilterWhere(['like', 'refund_status', $this->refund_status]);

        return $dataProvider;
    }
}
