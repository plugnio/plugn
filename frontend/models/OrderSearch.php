<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_uuid', 'area_id', 'payment_method_id', 'order_status'], 'integer'],
            [['area_name', 'area_name_ar', 'unit_type', 'block', 'street', 'avenue', 'house_number', 'special_directions', 'customer_name', 'customer_phone_number', 'customer_email', 'payment_method_name'], 'safe'],
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
        $query = Order::find();

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
            'order_uuid' => $this->order_uuid,
            'area_id' => $this->area_id,
            'payment_method_id' => $this->payment_method_id,
            'order_status' => $this->order_status,
        ]);

        $query->andFilterWhere(['like', 'area_name', $this->area_name])
            ->andFilterWhere(['like', 'area_name_ar', $this->area_name_ar])
            ->andFilterWhere(['like', 'unit_type', $this->unit_type])
            ->andFilterWhere(['like', 'block', $this->block])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'avenue', $this->avenue])
            ->andFilterWhere(['like', 'house_number', $this->house_number])
            ->andFilterWhere(['like', 'special_directions', $this->special_directions])
            ->andFilterWhere(['like', 'customer_name', $this->customer_name])
            ->andFilterWhere(['like', 'customer_phone_number', $this->customer_phone_number])
            ->andFilterWhere(['like', 'customer_email', $this->customer_email])
            ->andFilterWhere(['like', 'payment_method_name', $this->payment_method_name]);

        return $dataProvider;
    }
}
