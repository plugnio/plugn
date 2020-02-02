<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OrderItem;

/**
 * OrderItemSearch represents the model behind the search form of `common\models\OrderItem`.
 */
class OrderItemSearch extends OrderItem
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_item_id', 'order_id', 'qty', 'order_status'], 'integer'],
            [['item_uuid', 'item_name', 'instructions'], 'safe'],
            [['item_price'], 'number'],
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
        $query = OrderItem::find();

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
            'order_item_id' => $this->order_item_id,
            'order_id' => $this->order_id,
            'item_price' => $this->item_price,
            'qty' => $this->qty,
            'order_status' => $this->order_status,
        ]);

        $query->andFilterWhere(['like', 'item_uuid', $this->item_uuid])
            ->andFilterWhere(['like', 'item_name', $this->item_name])
            ->andFilterWhere(['like', 'instructions', $this->instructions]);

        return $dataProvider;
    }
}
