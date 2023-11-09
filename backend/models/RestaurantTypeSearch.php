<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RestaurantType;

/**
 * RestaurantTypeSearch represents the model behind the search form of `common\models\RestaurantType`.
 */
class RestaurantTypeSearch extends RestaurantType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_type_uuid', 'restaurant_uuid', 'merchant_type_uuid', 'business_type_uuid', 'business_category_uuid', 'created_at', 'updated_at'], 'safe'],
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
        $query = RestaurantType::find();

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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'restaurant_type_uuid', $this->restaurant_type_uuid])
            ->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'merchant_type_uuid', $this->merchant_type_uuid])
            ->andFilterWhere(['like', 'business_type_uuid', $this->business_type_uuid])
            ->andFilterWhere(['like', 'business_category_uuid', $this->business_category_uuid]);

        return $dataProvider;
    }
}
