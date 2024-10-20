<?php

namespace api\models;


use common\models\AgentAssignment;
use common\models\Partner;
use common\models\PaymentGatewayQueue;
use common\models\RestaurantPaymentMethod;
use common\models\Subscription;
use yii\db\ActiveQuery;
use yii\db\Expression;

class Restaurant extends \common\models\Restaurant {

  /**
   * @param bool $insert
   * @return bool|void
   */
  public function fields() {
      $fields = parent::fields();

      // remove fields that contain sensitive information
      unset($fields['restaurant_email_notification']);
      unset($fields['developer_id']);
      unset($fields['site_id']);
      unset($fields['retention_email_sent']);
      unset($fields['hide_request_driver_button']);
      unset($fields['platform_fee']);
      unset($fields['warehouse_fee']);
      unset($fields['warehouse_delivery_charges']);
      unset($fields['store_branch_name']);
      unset($fields['armada_api_key']);
      unset($fields['mashkor_branch_id']);
     // unset($fields['app_id']);
     // unset($fields['restaurant_status']);
      unset($fields['vendor_sector']);
      unset($fields['business_id']);
      unset($fields['business_entity_id']);
      unset($fields['wallet_id']);
      //unset($fields['merchant_id']);
      unset($fields['operator_id']);
      unset($fields['supplierCode']);
      unset($fields['live_api_key']);
      unset($fields['test_api_key']);
      //unset($fields['test_public_key']);
      unset($fields['sitemap_require_update']);
      unset($fields['business_type']);
     // unset($fields['restaurant_email']);
     // unset($fields['license_number']);
      unset($fields['not_for_profit']);
      unset($fields['authorized_signature_issuing_date']);
      unset($fields['authorized_signature_issuing_date']);
      unset($fields['authorized_signature_expiry_date']);
      unset($fields['authorized_signature_title']);
      unset($fields['authorized_signature_file']);
      unset($fields['authorized_signature_file_id']);
      unset($fields['authorized_signature_file_purpose']);
      unset($fields['commercial_license_issuing_date']);
      unset($fields['commercial_license_issuing_date']);
      unset($fields['commercial_license_expiry_date']);
      unset($fields['commercial_license_title']);
      unset($fields['commercial_license_file']);
      unset($fields['commercial_license_file_id']);
      unset($fields['commercial_license_file_purpose']);
      unset($fields['iban']);
      unset($fields['owner_first_name']);
      unset($fields['owner_last_name']);
      unset($fields['owner_email']);
      unset($fields['owner_number']);
      unset($fields['has_deployed']);
      unset($fields['payment_gateway_queue_id']);
      unset($fields['tap_queue_id']);

      unset($fields['is_tap_enable']);
      unset($fields['is_myfatoorah_enable']);
      unset($fields['company_name']);
 
      unset($fields['owner_phone_country_code']);
      unset($fields['identification_issuing_date']);
      unset($fields['identification_expiry_date']);
      unset($fields['identification_file_front_side']);
      unset($fields['identification_file_back_side']);
      unset($fields['identification_file_id_front_side']);
      unset($fields['identification_file_id_back_side']);
      unset($fields['identification_title']);
      unset($fields['identification_file_purpose']);
      unset($fields['restaurant_created_at']);
      unset($fields['restaurant_updated_at']);

      unset($fields['owner_nationality']);
      unset($fields['owner_date_of_birth']);
      unset($fields['swift_code']);
      unset($fields['account_number']);
      unset($fields['restaurant_updated_at']);

      unset($fields['referral_code']);
      //unset($fields['live_public_key']);

      return $fields;
  }

    /**
     * Get Agent Assignment Records
     * @return ActiveQuery
     */
    public function getAgentAssignments($modelClass = "\common\models\AgentAssignment")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->with('agent');
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return ActiveQuery
     */
    public function getOrders($modelClass = "\api\models\Order")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere(['order.is_deleted' => 0]);
    }

    /**
     * Gets query for [[Customers]].
     *
     * @return ActiveQuery
     */
    public function getCustomers($modelClass = "\api\models\Customer")
    {
        return $this->hasMany($modelClass::className(), ['customer_id' => 'customer_id'])
            ->via('orders');
    }

    /**
     * Gets query for [[Items]].
     *
     * @return ActiveQuery
     */
    public function getItems($modelClass = "\api\models\Item")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantPaymentMethods]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantPaymentMethods($modelClass = "\common\models\RestaurantPaymentMethod")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere(['status' => RestaurantPaymentMethod::STATUS_ACTIVE]);
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getTapRequirements($modelClass = "\common\models\TapRequirements")
    {
        return $this->hasOne($modelClass::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[RestaurantDomainRequest]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantDomainRequests($modelClass = "\common\models\RestaurantDomainRequest")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->orderBy('created_at DESC');
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getStoreKyc($modelClass = "\common\models\StoreKyc")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[StoreBillingAddress]].
     *
     * @return ActiveQuery
     */
    public function getStoreBillingAddress($modelClass = "\common\models\RestaurantBillingAddress")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Payments]].
     *
     * @return ActiveQuery
     */
    public function getPayments($modelClass = "\api\models\Payment")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Get Agents assigned to this Restaurant
     * @return ActiveQuery
     */
    public function getAgents($modelClass = "\common\models\Agent")
    {
        return $this->hasMany($modelClass::className(), ['agent_id' => 'agent_id'])
            ->via('agentAssignments');
    }

    /**
     * Return owner of this store
     */
    public function getOwnerAgent($modelClass = "\common\models\Agent")
    {
        return $this->hasMany($modelClass::className(), ['agent_id' => 'agent_id'])
            ->via('agentAssignments', function ($query) {
                return $query->andWhere(['agent_assignment.role' => AgentAssignment::AGENT_ROLE_OWNER]);
            });
    }

    /**
     * Gets query for [[RestaurantBillingAddress]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantBillingAddress($modelClass = "\common\models\RestaurantBillingAddress")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return ActiveQuery
     */
    public function getSubscriptions($modelClass = "\common\models\Subscription")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return ActiveQuery
     */
    public function getActiveSubscription($modelClass = "\common\models\Subscription")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere([
                'AND',
                ['subscription_status' => Subscription::STATUS_ACTIVE],
                new Expression('subscription_end_at IS NULL || DATE(subscription_end_at) >= DATE(NOW())')
            ])
            ->orderBy('subscription_start_at DESC');
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return ActiveQuery
     */
    public function getPlan($modelClass = "\common\models\Plan")
    {
        return $this->hasOne($modelClass::className(), ['plan_id' => 'plan_id'])
            ->via('activeSubscription');
    }

    /**
     * Gets query for [[Setting]].
     *
     * @return ActiveQuery
     */
    public function getSettings($modelClass = "\common\models\Setting")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantDeliveryAreas]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantDeliveryAreas($modelClass = "\common\models\RestaurantDelivery")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return ActiveQuery
     */
    public function getAvailableAreas($modelClass = "\common\models\AreaDeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere(['is', 'area_delivery_zone.area_id', null]);
    }

    /**
     * Gets query for [[RestaurantBranches]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantBranches($modelClass = "\common\models\RestaurantBranch")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getRestaurantShippingMethods($modelClass = "\common\models\RestaurantShippingMethod")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[restaurantPages]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantPages($modelClass = "\common\models\RestaurantPage")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Campaign]].
     *
     * @return ActiveQuery
     */
    public function getCampaigns($modelClass = "\common\models\Campaign")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[SourceCampaign]].
     *
     * @return ActiveQuery
     */
    public function getSourceCampaign($modelClass = "\common\models\Campaign")
    {
        return $this->hasOne($modelClass::className(), ['utm_uuid' => 'utm_uuid'])
            ->via('restaurantByCampaign');
    }

    /**
     * Gets query for [[ShippingMethods]].
     *
     * @return ActiveQuery
     */
    public function getShippingMethods($modelClass = "\common\models\ShippingMethod")
    {
        return $this->hasMany($modelClass::className(), ['shipping_method_id' => 'shipping_method_id'])
            ->viaTable('restaurant_shipping_method', ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantByCampaign]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantByCampaign($modelClass = "\common\models\RestaurantByCampaign")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[PaymentMethods]].
     *
     * @return ActiveQuery
     */
    public function getPaymentMethods($modelClass = "\common\models\PaymentMethod")
    {
        return $this->hasMany($modelClass::className(), ['payment_method_id' => 'payment_method_id'])
            ->viaTable(RestaurantPaymentMethod::tableName(), ['restaurant_uuid' => 'restaurant_uuid'],
                function ($query) {
                    $query->onCondition(['status' => RestaurantPaymentMethod::STATUS_ACTIVE]);
                });
    }

    /**
     * Gets query for [[OpeningHours]].
     *
     * @return ActiveQuery
     */
    public function getOpeningHours($modelClass = "\common\models\OpeningHour")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return ActiveQuery
     */
    public function getActiveOrders($modelClass = "\api\models\Order")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->activeOrders($this->restaurant_uuid);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return ActiveQuery
     */
    public function getTickets($modelClass = "\common\models\Ticket")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return ActiveQuery
     */
    public function getOrderItems($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->joinWith('order')
            ->activeOrders();
    }

    /**
     * Gets query for [[Vouchers]].
     *
     * @return ActiveQuery
     */
    public function getVouchers($modelClass = "\common\models\Voucher")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[StoreUpdates]].
     *
     * @return ActiveQuery
     */
    public function getStoreUpdates($modelClass = "\common\models\StoreUpdate")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Refunds]].
     *
     * @return ActiveQuery
     */
    public function getRefunds($modelClass = "\common\models\Refund")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Queues]].
     *
     * @return ActiveQuery
     */
    public function getQueues($modelClass = "\common\models\Queue")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getQueue($modelClass = "\common\models\Queue")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])->orderBy('queue_id DESC');
    }

    /**
     * Gets query for [[RestaurantTheme]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantTheme($modelClass = "\common\models\RestaurantTheme")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[TapQueue]].
     *
     * @return ActiveQuery
     */
    public function getTapQueue($modelClass = "\common\models\TapQueue")
    {
        return $this->hasOne($modelClass::className(), ['tap_queue_id' => 'tap_queue_id']);
    }

    /**
     * Gets query for [[TapQueue]].
     *
     * @return ActiveQuery
     */
    public function getPaymentGatewayQueue()
    {
        return $this->hasOne(PaymentGatewayQueue::className(), ['payment_gateway_queue_id' => 'payment_gateway_queue_id']);
        //->orderBy('payment_gateway_queue_id DESC');
    }

    /**
     * Gets query for [[WebLinks]].
     *
     * @return ActiveQuery
     */
    public function getWebLinks($modelClass = "\common\models\WebLink")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[StorePages]].
     *
     * @return ActiveQuery
     */
    public function getStorePages($modelClass = "\common\models\RestaurantPage")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[StoreWebLinks]].
     *
     * @return ActiveQuery
     */
    public function getStoreWebLinks($modelClass = "\common\models\StoreWebLink")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return ActiveQuery
     */
    public function getCountryDeliveryZones($countryId, $modelClass = "\common\models\DeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->where(['country_id' => $countryId, 'delivery_zone.is_deleted' => 0]);
    }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return ActiveQuery
     */
    public function getBusinessLocations($modelClass = "\common\models\BusinessLocation")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere(['business_location.is_deleted' => 0]);
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getBusinessCategory($modelClass = "\common\models\BusinessCategory")
    {
        return $this->hasOne($modelClass::className(), ['business_category_uuid' => 'business_category_uuid'])
            ->via('restaurantType');
    }

    // /**
    //  * Gets query for [[BusinessLocations]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getDeliveryZonesForSpecificCountry($countryId)
    // {
    //   return $this->hasMany(DeliveryZone::className(), ['business_location_id' => 'business_location_id'])
    //       ->andWhere(['delivery_zone.is_deleted' => 0]);
    //       ->viaTable('business_location', ['restaurant_uuid' => 'restaurant_uuid']);
    // }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return ActiveQuery
     */
    public function getPickupBusinessLocations($modelClass = "\common\models\BusinessLocation")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->andWhere(['support_pick_up' => 1, 'business_location.is_deleted' => 0]);
    }

    /**
     * Gets query for [[DeliveryZones]].
     *
     * @return ActiveQuery
     */
    public function getDeliveryZones($modelClass = "\common\models\DeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->joinWith('businessLocation')
            ->andWhere(['delivery_zone.is_deleted' => 0, 'business_location.is_deleted' => 0]);
    }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return ActiveQuery
     */
    public function getAreaDeliveryZonesForSpecificCountry($countryId, $modelClass = "\common\models\AreaDeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['delivery_zone_id' => 'delivery_zone_id'])
            ->via('deliveryZones')
            ->andWhere(['delivery_zone.country_id' => $countryId])
            ->joinWith(['deliveryZone', 'city']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return ActiveQuery
     */
    public function getAreas($modelClass = "\common\models\Area")
    {
        return $this->hasMany($modelClass::className(), ['area_id' => 'area_id'])->via('areaDeliveryZones');
    }

    /**
     * Gets query for [[AreaDeliveryZones]].
     *
     * @return ActiveQuery
     */
    public function getAreaDeliveryZones($modelClass = "\common\models\AreaDeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Partner]].
     *
     * @return ActiveQuery
     */
    public function getPartner()
    {
        return $this->hasOne(Partner::className(), ['referral_code' => 'referral_code'])
            ->where(['partner_status' => Partner::STATUS_ACTIVE]);
    }

    /**
     * Gets query for [[RestaurantUploads]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantUploads($modelClass = "\common\models\RestaurantUpload")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery
     */
    public function getCategories($modelClass = "\api\models\Category")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->orderBy('sort_number, category_id');
    }

    /**
     * @param $modelClass
     * @return mixed|void|null
     */
    public function getCountryName($modelClass = "\common\models\Country")
    {
        $country = $this->getCountry($modelClass)->one();

        if ($country)
            return $country->country_name;
    }

    /**
     * Gets query for [[Country]].
     *
     * @return ActiveQuery
     */
    public function getCountry($modelClass = "\common\models\Country")
    {
        return $this->hasOne($modelClass::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return ActiveQuery
     */
    public function getCurrency($modelClass = "\common\models\Currency")
    {
        return $this->hasOne($modelClass::className(), ['currency_id' => 'currency_id']);
    }

    /**
     * Gets query for [[Currencies]].
     *
     * @return ActiveQuery
     */
    public function getCurrencies($modelClass = "\common\models\Currency")
    {
        return $this->hasMany($modelClass::className(), ['currency_id' => 'currency_id'])
            ->via('restaurantCurrencies');
    }

    /**
     * Gets query for [[BankDiscount]].
     *
     * @return ActiveQuery
     */
    public function getBankDiscounts($modelClass = "\common\models\BankDiscount")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantCurrencies]].
     *
     * @return ActiveQuery
     */
    public function getRestaurantCurrencies($modelClass = "\common\models\RestaurantCurrency")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Invoices]].
     *
     * @return ActiveQuery
     */
    public function getInvoices($modelClass = "\common\models\RestaurantInvoice")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getRestaurantItemTypes($modelClass = "\common\models\RestaurantItemType")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * @param $modelClass
     * @return ActiveQuery
     */
    public function getRestaurantType($modelClass = "\common\models\RestaurantType")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
