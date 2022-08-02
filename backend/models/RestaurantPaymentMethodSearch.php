<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantPaymentMethod;

/**
 * RestaurantPaymentMethodSearch represents the model behind the search form of `common\models\RestaurantPaymentMethod`.
 */
class RestaurantPaymentMethodSearch extends RestaurantPaymentMethod
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid'], 'safe'],
            [['payment_method_id', 'status'], 'integer'],
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
        $query = RestaurantPaymentMethod::find();

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
            'payment_method_id' => $this->payment_method_id,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid]);

        return $dataProvider;
    }
}
