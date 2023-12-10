<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Restaurant;
use yii\db\Expression;

/**
 * RestaurantSearch represents the model behind the search form of `common\models\Restaurant`.
 */
class RestaurantSearch extends Restaurant
{
    public $country_name;
    public $currency_title;

    public $has_not_deployed;
    public $noOrder;
    public $noItem;
    public $notActive;

    /**
     * {@inheritdoc}
     */
     public function rules()
     {
         return [
             [['country_id', 'currency_id', 'license_number', 'vendor_sector', 'store_layout', 'enable_gift_message',
                 'retention_email_sent', 'referral_code', 'is_public', 'accept_order_247', 'iban', 'business_id',
                 'business_entity_id', 'wallet_id', 'merchant_id', 'operator_id',
                'restaurant_uuid', 'is_tap_enable', 'name', 'name_ar' ,'app_id', 'has_not_deployed',
                 'last_active_at', 'last_order_at', 'restaurant_email', 'restaurant_created_at', 'restaurant_updated_at',
                 'restaurant_domain', 'country_name', 'currency_title', 'is_myfatoorah_enable', 'has_deployed',
                 'is_sandbox', 'is_under_maintenance', 'enable_debugger', 'is_deleted', 'noOrder', 'total_orders', 'noItem', 'notActive', 'ip_address'], 'safe'],
             [['restaurant_status'], 'integer'],
             [['platform_fee','version', 'total_orders'], 'number'],
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
    public function search($params, $source_utm_uuid = null)
    {
        $query = Restaurant::find()->joinWith(['country', 'currency']);

        if($source_utm_uuid) {
            $query->joinWith(['restaurantByCampaign'])
                ->andWhere(['utm_uuid' => $source_utm_uuid]);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['restaurant_created_at' => SORT_DESC]],
        ]);

      //  $dataProvider->defa

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

        $query->andFilterWhere(['restaurant_status' => $this->restaurant_status]);

        if($this->is_tap_enable)
            $query->andFilterWhere(['is_tap_enable' => $this->is_tap_enable]);

        if($this->is_myfatoorah_enable)
            $query->andFilterWhere(['is_myfatoorah_enable' => $this->is_myfatoorah_enable]);

        if($this->has_not_deployed)
            $query->andFilterWhere(['has_deployed' => false]);

        if(isset($this->has_deployed))
            $query->andFilterWhere(['has_deployed' => $this->has_deployed]);

        if($this->is_sandbox)
            $query->andFilterWhere(['is_sandbox'  => $this->is_sandbox]);

        if($this->is_under_maintenance)
            $query->andFilterWhere(['is_under_maintenance' => $this->is_under_maintenance]);

        if($this->enable_debugger)
            $query->andFilterWhere(['enable_debugger' => $this->enable_debugger]);

        if($this->is_deleted) {
            $query->andFilterWhere(['is_deleted' => $this->is_deleted]);
        } else {
            $query->andFilterWhere(['is_deleted' => 0]);
        }

        if($this->ip_address) {
            $query->andFilterWhere(['restaurant.ip_address' => $this->ip_address]);
        }

        if($this->notActive) {
            $query->andWhere("last_active_at IS NULL OR DATE(last_active_at) < DATE('".
                date('Y-m-d', strtotime("-30 days"))."')");
        }

        if($this->noOrder) {
            $query->andWhere( "last_order_at IS NULL OR DATE(last_order_at) < DATE('".
                date('Y-m-d', strtotime("-30 days"))."')");
        }

        if($this->noItem) {
            $query
                ->joinWith(['items'])
                ->andWhere( new Expression("item_uuid IS NULL"));
        }

        if($this->last_active_at) {
            $query->andWhere("DATE(last_active_at) = DATE('".
                date('Y-m-d', strtotime($this->last_active_at))."')");
        }

        if($this->last_order_at) {
            $query->andWhere( "DATE(last_order_at) = DATE('".
               date('Y-m-d', strtotime($this->last_order_at))."')");
        }

        if($this->is_public)
            $query->andFilterWhere(['is_public' => $this->is_public]);
        if($this->accept_order_247)
            $query->andFilterWhere(['accept_order_247' => $this->accept_order_247]);
        if($this->enable_gift_message)
            $query->andFilterWhere(['enable_gift_message' => $this->enable_gift_message]);

        $query->andFilterWhere(['like', 'country_id', $this->country_id])
            ->andFilterWhere(['like', 'currency_id', $this->currency_id])
            ->andFilterWhere(['like', 'license_number', $this->license_number])
            ->andFilterWhere(['like', 'vendor_sector', $this->vendor_sector])
            ->andFilterWhere(['like', 'store_layout', $this->store_layout])
            ->andFilterWhere(['like', 'retention_email_sent', $this->retention_email_sent])
            ->andFilterWhere(['like', 'referral_code', $this->referral_code])
            ->andFilterWhere(['like', 'iban', $this->iban])
            ->andFilterWhere(['business_entity_id', 'business_entity_id', $this->business_entity_id])
            ->andFilterWhere(['like', 'wallet_id', $this->wallet_id])
            ->andFilterWhere(['like', 'merchant_id', $this->merchant_id])
            ->andFilterWhere(['like', 'operator_id', $this->operator_id])
            ->andFilterWhere(['like', 'business_id', $this->business_id]);

        $query->andFilterWhere(['like', 'restaurant_uuid', $this->restaurant_uuid])
            ->andFilterWhere(['like', 'restaurant_domain', $this->restaurant_domain])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'version', $this->version])
            ->andFilterWhere(['like', 'currency.title', $this->currency_title])
            ->andFilterWhere(['like', 'total_orders', $this->total_orders])
            ->andFilterWhere(['like', 'name_ar', $this->name_ar]);

        if($this->country_name)
            $query->andFilterWhere(['like', 'country.country_name',
                new Expression("'%". $this->country_name . "%'")]);


        return $dataProvider;
    }
}
