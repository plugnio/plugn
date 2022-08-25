<?php

namespace backend\models;

use common\models\RestaurantAddon;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class RestaurantAddonSearch extends RestaurantAddon
{
    public $restaurantName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['addon_uuid', 'restaurant_uuid'], 'integer'],
            [['restaurantName', 'created_at'], 'safe'],
        ];
    }

    /**
     * return store name
     * @return string
     */
    public function getRestaurantName()
    {
        return $this->restaurant->name;
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
        $query = RestaurantAddon::find()
            ->with('restaurant');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->sort->attributes['restaurantName'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
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
            //'restaurant.name' => $this->restaurant,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'restaurant.name', $this->restaurantName]);

        return $dataProvider;
    }
}