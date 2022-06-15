<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Restaurant;

/**
 * RestaurantSearch represents the model behind the search form of `common\models\Restaurant`.
 */
class RestaurantSearch extends Restaurant
{

    public $country_name;
    public $currency_title;


    /**
     * {@inheritdoc}
     */
     public function rules()
     {
         return [
             [['restaurant_uuid', 'name', 'name_ar' ,'app_id', 'restaurant_email', 'restaurant_created_at', 'restaurant_updated_at','restaurant_domain', 'country_name', 'currency_title'], 'safe'],
             [['restaurant_status'], 'integer'],
             [['platform_fee','version'], 'number'],
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
    public function search($params)
    {
        $query = Restaurant::find()->joinWith(['country', 'currency'])->orderBy(['restaurant_created_at' => SORT_DESC]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $dataProvider->sort->attributes['country_name'] = [
            'asc' => ['country.country_name' => SORT_ASC],
            'desc' => ['country.country_name' => SORT_DESC],
        ];


        $dataProvider->sort->attributes['currency_title'] = [
            'asc' => ['currency.title' => SORT_ASC],
            'desc' => ['currency.title' => SORT_DESC],
        ];



        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'restaurant_created_at' => $this->restaurant_created_at,
            'restaurant_updated_at' => $this->restaurant_updated_at,
        ]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'restaurant_domain', $this->restaurant_domain])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'currency.title', $this->currency_title])
            ->andFilterWhere(['like', 'country.country_name', $this->country_name])
            ->andFilterWhere(['like', 'name_ar', $this->name_ar]);



        return $dataProvider;
    }
}
