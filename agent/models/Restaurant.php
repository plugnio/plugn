<?php

namespace agent\models;


class Restaurant extends \common\models\Restaurant {

    /**
     * @inheritdoc
     */
    public function fields() {
        $fields = parent::fields();

        $fields['store_email'] = function($model) {
            return $model->restaurant_email;
        };

        $fields['order_count'] = function($model) {
            return $model->getOrders()->count();
        };

        return $fields;
    }

    public function extraFields()
    {
        $fields = parent::extraFields ();

        return array_merge ($fields, [
            'restaurantPaymentMethods',
            'activeSubscription',
            'restaurantTheme',
            'restaurantTheme',
            'countryByOwnerCountryCode',
            'countryByPhoneCountryCode'
        ]);
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems($modelClass = "\agent\models\Item")
    {
        return parent::getItems($modelClass);
    }

    /**
     * Get Agent Assignment Records
     * @return \yii\db\ActiveQuery
     */
    public function getAgentAssignments($modelClass = "\agent\models\AgentAssignment")
    {
        return parent::getAgentAssignments($modelClass);
    }

    /**
     * Get Agents assigned to this Restaurant
     * @return \yii\db\ActiveQuery
     */
    public function getAgents($modelClass = "\agent\models\Agent")
    {
        return parent::getAgents($modelClass);
    }

    /**
     * Return owner of this store
     */
    public function getOwnerAgent($modelClass = "\agent\models\Agent")
    {
        return parent::getOwnerAgent ($modelClass);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions($modelClass = "\agent\models\Subscription")
    {
        return parent::getSubscriptions($modelClass);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveSubscription($modelClass = "\agent\models\Subscription")
    {
        return parent::getActiveSubscription($modelClass);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan($modelClass = "\agent\models\Plan")
    {
        return parent::getPlan($modelClass);
    }

    /**
     * Gets query for [[RestaurantDeliveryAreas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveryAreas($modelClass = "\agent\models\RestaurantDelivery")
    {
        return parent::getRestaurantDeliveryAreas($modelClass);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAvailableAreas($modelClass = "\agent\models\AreaDeliveryZone")
    {
        return parent::getAvailableAreas($modelClass);
    }

    /**
     * Gets query for [[RestaurantBranches]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantBranches($modelClass = "\agent\models\RestaurantBranch")
    {
        return parent::getRestaurantBranches($modelClass);
    }

    /**
     * Gets query for [[RestaurantPaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantPaymentMethods($modelClass = "\agent\models\RestaurantPaymentMethod")
    {
        return parent::getRestaurantPaymentMethods($modelClass);
    }

    /**
     * Gets query for [[PaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods($modelClass = "\agent\models\PaymentMethod")
    {
        return parent::getPaymentMethods($modelClass);
    }

    /**
     * Gets query for [[OpeningHours]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOpeningHours($modelClass = "\agent\models\OpeningHour")
    {
        return parent::getOpeningHours($modelClass);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders($modelClass = "\agent\models\Order")
    {
        return parent::getOrders($modelClass);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveOrders($modelClass = "\agent\models\Order")
    {
        return parent::getActiveOrders($modelClass);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems($modelClass = "\agent\models\OrderItem")
    {
        return parent::getOrderItems($modelClass);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSoldOrderItems($modelClass = "\agent\models\OrderItem")
    {
        return parent::getSoldOrderItems($modelClass);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStoreRevenue($start_date, $end_date, $modelClass = "\agent\models\Order")
    {
        return parent::getStoreRevenue($start_date, $end_date, $modelClass);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrdersReceived($start_date, $end_date, $modelClass = "\agent\models\Order")
    {
        return parent::getOrdersReceived($start_date, $end_date, $modelClass);
    }

    /**
     * Gets query for [[Vouchers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVouchers($modelClass = "\agent\models\Voucher")
    {
        return parent::getVouchers($modelClass);
    }

    /**
     * Gets query for [[Customers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomers($modelClass = "\agent\models\Customer")
    {
        return parent::getCustomers($modelClass);
    }

    /**
     * Gets query for [[Customers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerGained($start_date, $end_date, $modelClass = "\agent\models\Customer")
    {
        return parent::getCustomerGained($start_date, $end_date, $modelClass);
    }

    /**
     * Gets query for [[Refunds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefunds($modelClass = "\agent\models\Refund")
    {
        return $this->hasMany ($modelClass::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Queues]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getQueues($modelClass = "\agent\models\Queue")
    {
        return parent::getQueues($modelClass);
    }

    /**
     * Gets query for [[RestaurantTheme]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantTheme($modelClass = "\agent\models\RestaurantTheme")
    {
        return parent::getRestaurantTheme($modelClass);
    }

    /**
     * Gets query for [[TapQueue]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTapQueue($modelClass = "\agent\models\TapQueue")
    {
        return parent::getTapQueue($modelClass);
    }

    /**
     * Gets query for [[WebLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWebLinks($modelClass = "\agent\models\WebLink")
    {
        return parent::getWebLinks($modelClass);
    }

    /**
     * Gets query for [[StoreWebLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStoreWebLinks($modelClass = "\agent\models\StoreWebLink")
    {
        return parent::getStoreWebLinks($modelClass);
    }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountryDeliveryZones($countryId, $modelClass = "\agent\models\DeliveryZone")
    {
        return parent::getCountryDeliveryZones($countryId, $modelClass);
    }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocations($modelClass = "\agent\models\BusinessLocation")
    {
        return parent::getBusinessLocations($modelClass);
    }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPickupBusinessLocations($modelClass = "\agent\models\BusinessLocation")
    {
        return parent::getPickupBusinessLocations($modelClass);
    }

    /**
     * Gets query for [[DeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZones($modelClass = "\agent\models\DeliveryZone")
    {
        return parent::getDeliveryZones($modelClass);
    }

    // /**
    //  * Gets query for [[BusinessLocations]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getDeliveryZonesForSpecificCountry($countryId)
    // {
    //   return $this->hasMany(DeliveryZone::className(), ['business_location_id' => 'business_location_id'])
    //       ->viaTable('business_location', ['restaurant_uuid' => 'restaurant_uuid']);
    // }

    /**
     * Gets query for [[BusinessLocations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZonesForSpecificCountry($countryId, $modelClass = "\agent\models\AreaDeliveryZone")
    {
        return parent::getAreaDeliveryZonesForSpecificCountry($countryId, $modelClass);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas($modelClass = "\agent\models\Area")
    {
        return parent::getAreas($modelClass);
    }

    /**
     * Gets query for [[AreaDeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones($modelClass = "\agent\models\AreaDeliveryZone")
    {
        return parent::getAreaDeliveryZones($modelClass);
    }

    /**
     * list of all the countries around the world that store can ship orders to
     *
     * @return \yii\db\ActiveQuery
     */
    // public function getShippingCountries($modelClass = "\agent\models\Country")
    // {
    //     return parent::getShippingCountries($modelClass);
    // }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry($modelClass = "\agent\models\Country")
    {
        return parent::getCountry($modelClass);
    }

    public function getCountryByOwnerCountryCode($modelClass = "\agent\models\Country")
    {
        return $this->hasOne ($modelClass::className (), ['country_code' => 'owner_phone_country_code']);
    }

    public function getCountryByPhoneCountryCode($modelClass = "\agent\models\Country")
    {
        return $this->hasOne ($modelClass::className (), ['country_code' => 'phone_number_country_code']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\agent\models\Currency")
    {
        return parent::getCurrency($modelClass);
    }
}
