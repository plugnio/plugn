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

    public $noOrder;
    public $notActive;

    /**
     * {@inheritdoc}
     */
     public function rules()
     {
         return [
             [['restaurant_uuid', 'is_tap_enable', 'name', 'name_ar' ,'app_id',
                 'last_active_at', 'last_order_at', 'restaurant_email', 'restaurant_created_at', 'restaurant_updated_at',
                 'restaurant_domain', 'country_name', 'currency_title', 'is_myfatoorah_enable', 'has_deployed',
                 'is_sandbox', 'is_under_maintenance', 'enable_debugger', 'is_deleted', 'noOrder', 'notActive'], 'safe'],
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
        $query = Restaurant::find()->joinWith(['country', 'currency'])
            ->orderBy(['restaurant_created_at' => SORT_DESC]);

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

        if($this->restaurant_created_at)    
            $query->andFilterWhere([
                'DATE(restaurant_created_at)' => date('Y-m-d', strtotime($this->restaurant_created_at))
            ]);
            
        if($this->restaurant_updated_at)    
            $query->andFilterWhere([
                'DATE(restaurant_updated_at)' => date('Y-m-d', strtotime($this->restaurant_updated_at)),
            ]);

        if($this->is_tap_enable)
            $query->andFilterWhere(['is_tap_enable' => $this->is_tap_enable]);

        if($this->is_myfatoorah_enable)
            $query->andFilterWhere(['is_myfatoorah_enable' => $this->is_myfatoorah_enable]);

        if($this->has_deployed)
            $query->andFilterWhere(['has_deployed' => $this->has_deployed]);

        if($this->is_sandbox)
            $query->andFilterWhere(['is_sandbox'  => $this->is_sandbox]);

        if($this->is_under_maintenance)
            $query->andFilterWhere(['is_under_maintenance' => $this->is_under_maintenance]);

        if($this->enable_debugger)
            $query->andFilterWhere(['enable_debugger' => $this->enable_debugger]);

        if($this->is_deleted)
            $query->andFilterWhere(['is_deleted' => $this->is_deleted]);

        if($this->notActive) {
            $query->andWhere("last_active_at IS NULL OR DATE(last_active_at) < DATE('".
                date('Y-m-d', strtotime("-30 days"))."')");
        }

        if($this->noOrder) {
            $query->andWhere( "last_order_at IS NULL OR DATE(last_order_at) < DATE('".
                date('Y-m-d', strtotime("-30 days"))."')");
        }

        if($this->last_active_at) {
            $query->andWhere("DATE(last_active_at) = DATE('".
                date('Y-m-d', strtotime($this->last_active_at))."')");
        }

        if($this->last_order_at) {
            $query->andWhere( "DATE(last_order_at) = DATE('".
               date('Y-m-d', strtotime($this->last_order_at))."')");
        }

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
