<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Order;
use common\models\AgentAssignment;

/**
 * OrderSearch represents the model behind the search form of `common\models\Order`.
 */
class OrderSearch extends Order {

    public $date_range;
    public $business_location_id;
    //public $payment_method_name;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['area_id', 'payment_method_id', 'order_status'], 'integer'],
            [['total_price_before_refund', 'total_price'], 'number'],
            [['date_range','business_location_id'], 'safe'],
            [['delivery_fee', 'order_mode', 'total_price', 'is_order_scheduled', 'order_uuid', 'area_name', 'area_name_ar', 'unit_type', 'block', 'street', 'avenue', 'house_number', 'special_directions', 'customer_name', 'customer_phone_number', 'customer_email', 'payment_method_name', 'payment_method_name_ar', 'business_location_name', 'order_created_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
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
    public function searchAbandonedCheckoutOrders($params, $storeUuid, $agentAssignment) {

        $query = Order::find()
            ->with([
                'payment',
                'paymentMethod',
                'currency',
                'customer'
            ])
            ->orderBy(['order_created_at' => SORT_DESC]);

        if($agentAssignment && $agentAssignment->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER){
            $query
                ->andWhere([
                    'OR',
                    ['delivery_zone.business_location_id' => $agentAssignment->business_location_id],
                    [ 'pickup_location_id' => $agentAssignment->business_location_id]
                ]);
        }

        $query
            ->andWhere(['order.restaurant_uuid' => $storeUuid])
            ->andWhere(['order.is_deleted' => 0])
            ->andWhere(['order_status' => Order::STATUS_ABANDONED_CHECKOUT]);


        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
             ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'order_uuid' => str_replace('#', '', $this->order_uuid),
            'area_id' => $this->area_id,
            'payment_method_id' => $this->payment_method_id,
            'order_status' => $this->order_status,
            'total_price' => str_replace([$this->currency_code, ','], ['', ''], $this->total_price),
            'is_order_scheduled' => $this->is_order_scheduled,
        ]);
        
        if($this->order_created_at) {
            $query->andWhere(new \yii\db\Expression("DATE(order_created_at) = '".date('Y-m-d', strtotime($this->order_created_at))."'"));
        }
        
        $query->andFilterWhere(['like', 'area_name', $this->area_name])
                ->andFilterWhere(['like', 'area_name_ar', $this->area_name_ar])
                ->andFilterWhere(['like', 'unit_type', $this->unit_type])
                ->andFilterWhere(['like', 'block', $this->block])
                ->andFilterWhere(['like', 'street', $this->street])
                ->andFilterWhere(['like', 'avenue', $this->avenue])
                ->andFilterWhere(['like', 'total_price_before_refund', $this->total_price_before_refund])
                ->andFilterWhere(['like', 'house_number', $this->house_number])
                ->andFilterWhere(['like', 'special_directions', $this->special_directions])
                ->andFilterWhere(['like', 'customer_name', $this->customer_name])
                ->andFilterWhere(['like', 'customer_phone_number', $this->customer_phone_number])
                ->andFilterWhere(['like', 'customer_email', $this->customer_email])
                ->andFilterWhere(['like', 'payment_method_name', $this->payment_method_name])
                ->andFilterWhere(['like', 'payment_method_name_ar', $this->payment_method_name_ar])
                ->andFilterWhere(['like', 'business_location_name', $this->business_location_name]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchDraftOrders($params, $storeUuid, $agentAssignment) {

        $query = Order::find()
            ->with(['payment','paymentMethod', 'currency', 'customer'])
            ->orderBy(['order.order_created_at' => SORT_DESC]);

        if($agentAssignment && $agentAssignment->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER){
            $query
                ->andWhere([
                    'OR',
                    ['delivery_zone.business_location_id' => $agentAssignment->business_location_id],
                    [ 'pickup_location_id' => $agentAssignment->business_location_id]
                ]);
        }

        $query
            ->andWhere(['order.restaurant_uuid' => $storeUuid])
            ->andWhere(['order.is_deleted' => 0])
            ->andWhere(['order.order_status' => Order::STATUS_DRAFT]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
             ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'order_uuid' => str_replace('#', '', $this->order_uuid),
            'area_id' => $this->area_id,
            'payment_method_id' => $this->payment_method_id,
            'order_status' => $this->order_status,
            'total_price' => str_replace([$this->currency_code, ','], ['', ''], $this->total_price),
            'is_order_scheduled' => $this->is_order_scheduled,
        //    'order_created_at' => $this->order_created_at?date('Y-m-d h:m', strtotime($this->order_created_at)) : null
        ]);


        if($this->order_created_at) {
            $query->andWhere(new \yii\db\Expression("DATE(order_created_at) = '".date('Y-m-d', strtotime($this->order_created_at))."'"));
        }
        
        $query->andFilterWhere(['like', 'area_name', $this->area_name])
                ->andFilterWhere(['like', 'area_name_ar', $this->area_name_ar])
                ->andFilterWhere(['like', 'unit_type', $this->unit_type])
                ->andFilterWhere(['like', 'block', $this->block])
                ->andFilterWhere(['like', 'street', $this->street])
                ->andFilterWhere(['like', 'avenue', $this->avenue])
                ->andFilterWhere(['like', 'total_price_before_refund', $this->total_price_before_refund])
                ->andFilterWhere(['like', 'house_number', $this->house_number])
                ->andFilterWhere(['like', 'special_directions', $this->special_directions])
                ->andFilterWhere(['like', 'customer_name', $this->customer_name])
                ->andFilterWhere(['like', 'customer_phone_number', $this->customer_phone_number])
                ->andFilterWhere(['like', 'customer_email', $this->customer_email])
                ->andFilterWhere(['like', 'payment_method_name', $this->payment_method_name])
                ->andFilterWhere(['like', 'payment_method_name_ar', $this->payment_method_name_ar])
                ->andFilterWhere(['like', 'business_location_name', $this->business_location_name]);


        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchPendingOrders($params, $storeUuid, $agentAssignment) {

        $query = Order::find()
            ->with([
                'payment',
                'paymentMethod',
                'currency',
                'customer'])
            ->orderBy(['order_created_at' => SORT_DESC]);

          if($agentAssignment && $agentAssignment->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER){
              $query
                  ->andWhere([
                      'OR',
                      ['delivery_zone.business_location_id' => $agentAssignment->business_location_id],
                      ['pickup_location_id' => $agentAssignment->business_location_id]
                  ]);
          }

          $query
              ->andWhere(['order.restaurant_uuid' => $storeUuid])
              ->andWhere(['order.is_deleted' => 0])
              ->andWhere(['order.order_status' => Order::STATUS_PENDING]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'order_uuid' => str_replace('#', '', $this->order_uuid),
            'area_id' => $this->area_id,
            'payment_method_id' => $this->payment_method_id,
            'order_status' => $this->order_status,
            'total_price' => str_replace([$this->currency_code, ','], ['', ''], $this->total_price),
            'is_order_scheduled' => $this->is_order_scheduled,
        //    'order_created_at' => $this->order_created_at?date('Y-m-d h:m', strtotime($this->order_created_at)) : null
        ]);


        if($this->order_created_at) {
            $query->andWhere(new \yii\db\Expression("DATE(order_created_at) = '".date('Y-m-d', strtotime($this->order_created_at))."'"));
        }
        
        $query->andFilterWhere(['like', 'area_name', $this->area_name])
                ->andFilterWhere(['like', 'area_name_ar', $this->area_name_ar])
                ->andFilterWhere(['like', 'unit_type', $this->unit_type])
                ->andFilterWhere(['like', 'block', $this->block])
                ->andFilterWhere(['like', 'street', $this->street])
                ->andFilterWhere(['like', 'avenue', $this->avenue])
                ->andFilterWhere(['like', 'total_price_before_refund', $this->total_price_before_refund])
                ->andFilterWhere(['like', 'house_number', $this->house_number])
                ->andFilterWhere(['like', 'special_directions', $this->special_directions])
                ->andFilterWhere(['like', 'customer_name', $this->customer_name])
                ->andFilterWhere(['like', 'customer_phone_number', $this->customer_phone_number])
                ->andFilterWhere(['like', 'customer_email', $this->customer_email])
                ->andFilterWhere(['like', 'payment_method_name', $this->payment_method_name])
                ->andFilterWhere(['like', 'payment_method_name_ar', $this->payment_method_name_ar])
                ->andFilterWhere(['like', 'business_location_name', $this->business_location_name]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $storeUuid, $agentAssignment = null) {

        $query = Order::find()
            ->with(['paymentMethod', 'currency', 'deliveryZone.businessLocation'])
            ->joinWith('deliveryZone', true)
            ->joinWith('pickupLocation', true)
            ->joinWith('customer', true)
            ->orderBy(['order_created_at' => SORT_DESC]);

        if($agentAssignment && $agentAssignment->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER){

            $query
                ->andWhere([
                    'OR',
                    ['delivery_zone.business_location_id' => $agentAssignment->business_location_id],
                    [ 'pickup_location_id' => $agentAssignment->business_location_id]
                ]);
        }

        $query->andWhere(['order.restaurant_uuid' => $storeUuid])
                    ->andWhere(['!=' , 'order_status' , Order::STATUS_DRAFT])
                    ->andWhere(['order.is_deleted' => 0])
                    ->andWhere(['!=' , 'order_status' , Order::STATUS_ABANDONED_CHECKOUT]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
             ],
        ]);

        $dataProvider->sort->attributes['business_location_id'] = [
            'asc' => ['pickupLocation.business_location_name' => SORT_ASC],
            'desc' => ['pickupLocation.business_location_name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // do we have values? if so, add a filter to our query
        if (!empty($this->date_range) && strpos($this->date_range, '-') !== false) {

            list($start_date, $end_date) = explode(' - ', $this->date_range);
            $query->andFilterWhere(['between', 'order_created_at', $start_date, $end_date]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'order_uuid' => str_replace('#', '', $this->order_uuid),
            'area_id' => $this->area_id,
            'payment_method_id' => $this->payment_method_id,
            'order_status' => $this->order_status,
            'total_price' => str_replace([$this->currency_code, ','], ['', ''], $this->total_price),
            'is_order_scheduled' => $this->is_order_scheduled,
        //    'order_created_at' => $this->order_created_at?date('Y-m-d h:m', strtotime($this->order_created_at)) : null
        ]);


        if($this->order_created_at) {
            $query->andWhere(new \yii\db\Expression("DATE(order_created_at) = '".date('Y-m-d', strtotime($this->order_created_at))."'"));
        }
        
        $query->andFilterWhere(['like', 'area_name', $this->area_name])
                ->andFilterWhere(['like', 'area_name_ar', $this->area_name_ar])
                ->andFilterWhere(['like', 'unit_type', $this->unit_type])
                ->andFilterWhere(['like', 'block', $this->block])
                ->andFilterWhere(['like', 'street', $this->street])
                ->andFilterWhere(['like', 'avenue', $this->avenue])
                ->andFilterWhere(['like', 'businessLocation.business_location_id', $this->business_location_id])
                ->andFilterWhere(['like', 'total_price_before_refund', $this->total_price_before_refund])
                ->andFilterWhere(['like', 'house_number', $this->house_number])
                ->andFilterWhere(['like', 'special_directions', $this->special_directions])
                ->andFilterWhere(['like', 'customer_name', $this->customer_name])
                ->andFilterWhere(['like', 'customer.customer_phone_number', $this->customer_phone_number])
                ->andFilterWhere(['like', 'customer_email', $this->customer_email])
                ->andFilterWhere(['like', 'payment_method_name', $this->payment_method_name])
                ->andFilterWhere(['like', 'payment_method_name_ar', $this->payment_method_name_ar])
                ->andFilterWhere(['like', 'business_location_name', $this->business_location_name]);

        return $dataProvider;
    }
}
