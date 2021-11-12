<?php

use yii\db\Migration;

/**
 * Class m211108_110809_fk_changes
 */
class m211108_110809_fk_changes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('SET foreign_key_checks = 0')->execute();

        $action = 'NO ACTION';
        // add foreign key for table `restaurant`
        $this->dropForeignKey('fk-agent_assignment-restaurant_uuid', 'agent_assignment');
        $this->addForeignKey(
            'fk-agent_assignment-restaurant_uuid',
            'agent_assignment',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            $action
        );

        $this->dropForeignKey('fk-agent_assignment-business_location_id', 'agent_assignment');
        $this->addForeignKey(
            'fk-agent_assignment-business_location_id',
            'agent_assignment',
            'business_location_id',
            'business_location',
            'business_location_id',
            $action
        );


        $this->dropForeignKey('fk-agent_assignment-business_location_id', 'agent_assignment');
        $this->addForeignKey(
            'fk-agent_assignment-business_location_id',
            'agent_assignment',
            'business_location_id',
            'business_location',
            'business_location_id',
            $action
        );


        // add foreign key for table `city`
        $this->dropForeignKey('fk-area-city_id', 'area');

        $this->addForeignKey(
            'fk-area-city_id', 'area', 'city_id', 'city', 'city_id', $action
        );

        /*==================delivery_zone==========*/

        $this->dropForeignKey('fk-delivery_zone-restaurant_uuid', 'delivery_zone');
        $this->addForeignKey(
            'fk-delivery_zone-restaurant_uuid',
            'delivery_zone',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            $action
        );


        $this->dropForeignKey('fk-delivery_zone-country_id', 'delivery_zone');
        // add foreign key for table `delivery_zone`
        $this->addForeignKey(
            'fk-delivery_zone-country_id',
            'delivery_zone',
            'country_id',
            'country',
            'country_id',
            $action
        );


        $this->dropForeignKey('fk-delivery_zone-business_location_id', 'delivery_zone');
        // add foreign key for table `delivery_zone`
        $this->addForeignKey(
            'fk-delivery_zone-business_location_id',
            'delivery_zone',
            'business_location_id',
            'business_location',
            'business_location_id',
            $action
        );


        $this->dropForeignKey('fk-area_delivery_zone-country_id', 'area_delivery_zone');
        // add foreign key for table `area_delivery_zone`
        $this->addForeignKey(
            'fk-area_delivery_zone-country_id',
            'area_delivery_zone',
            'country_id',
            'country',
            'country_id',
            $action
        );

        $this->dropForeignKey('fk-area_delivery_zone-city_id', 'area_delivery_zone');
        // add foreign key for table `area_delivery_zone`
        $this->addForeignKey(
            'fk-area_delivery_zone-city_id',
            'area_delivery_zone',
            'city_id',
            'city',
            'city_id',
            $action
        );

        $this->dropForeignKey('fk-area_delivery_zone-restaurant_uuid', 'area_delivery_zone');
        // add foreign key for table `area_delivery_zone`
        $this->addForeignKey(
            'fk-area_delivery_zone-restaurant_uuid',
            'area_delivery_zone',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            $action
        );


        $this->dropForeignKey('fk-area_delivery_zone-area_id', 'area_delivery_zone');
        // add foreign key for table `area_delivery_zone`
        $this->addForeignKey(
            'fk-area_delivery_zone-area_id',
            'area_delivery_zone',
            'area_id',
            'area',
            'area_id',
            $action
        );

        $this->dropForeignKey('fk-area_delivery_zone-delivery_zone_id', 'area_delivery_zone');
        // add foreign key for table `area_delivery_zone`
        $this->addForeignKey(
            'fk-area_delivery_zone-delivery_zone_id',
            'area_delivery_zone',
            'delivery_zone_id',
            'delivery_zone',
            'delivery_zone_id',
            $action
        );


        $this->dropForeignKey('fk-city-country_id', 'city');
        // add foreign key for table `city`
        $this->addForeignKey(
            'fk-city-country_id',
            'city',
            'country_id',
            'country',
            'country_id',
            $action
        );

        // add foreign key for table `customer`
        $this->dropForeignKey('fk-customer-restaurant_uuid', 'customer');
        $this->addForeignKey('fk-customer-restaurant_uuid', 'customer', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', $action);


        $this->dropForeignKey('fk-customer_voucher-customer_id', 'customer_voucher');
        // add foreign key for table `customer_voucher`
        $this->addForeignKey(
            'fk-customer_voucher-customer_id',
            'customer_voucher',
            'customer_id',
            'customer',
            'customer_id',
            $action
        );

        $this->dropForeignKey('fk-customer_voucher-voucher_id', 'customer_voucher');
        // add foreign key for table `customer_voucher`
        $this->addForeignKey(
            'fk-customer_voucher-voucher_id',
            'customer_voucher',
            'voucher_id',
            'voucher',
            'voucher_id',
            $action
        );


        // add foreign key for table `order`
        $this->dropForeignKey('fk-order-country_id', 'order');
        $this->addForeignKey(
            'fk-order-country_id',
            'order',
            'shipping_country_id',
            'country',
            'country_id',
            $action
        );

        $this->dropForeignKey('fk-order-customer_id', 'order');
        $this->addForeignKey('fk-order-customer_id', 'order', 'customer_id', 'customer', 'customer_id', $action);

        $this->dropForeignKey('fk-order-payment_method_id', 'order');
        $this->addForeignKey('fk-order-payment_method_id', 'order', 'payment_method_id', 'payment_method', 'payment_method_id', $action);

        $this->dropForeignKey('fk-order-payment_uuid', 'order');
        $this->addForeignKey('fk-order-payment_uuid', 'order', 'payment_uuid', 'payment', 'payment_uuid', $action);

        $this->dropForeignKey('fk-order-restaurant_branch_id', 'order');
        $this->addForeignKey('fk-order-restaurant_branch_id', 'order', 'restaurant_branch_id', 'restaurant_branch', 'restaurant_branch_id', $action);

        $this->dropForeignKey('fk-order-restaurant_uuid', 'order');
        $this->addForeignKey('fk-order-restaurant_uuid', 'order', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', $action);

        // add foreign key for `restaurant_uuid` in table `order_item`
        $this->dropForeignKey('fk-order_item-restaurant_uuid', 'order_item');
        $this->addForeignKey('fk-order_item-restaurant_uuid', 'order_item', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', $action);

        $this->dropForeignKey('fk-partner_payout-partner_uuid', 'partner_payout');
        $this->addForeignKey('fk-partner_payout-partner_uuid', 'partner_payout', 'partner_uuid', 'partner', 'partner_uuid', $action);


        $this->dropForeignKey('fk-payment-customer_id', 'payment');
        $this->addForeignKey('fk-payment-customer_id', 'payment', 'customer_id', 'customer', 'customer_id', $action);

        // add foreign key for `order_uuid` in table `payment`
        $this->dropForeignKey('fk-payment-order_uuid', 'payment');
        $this->addForeignKey('fk-payment-order_uuid', 'payment', 'order_uuid', 'order', 'order_uuid', $action);


        $this->dropForeignKey('fk-payment_gateway_queue-restaurant_uuid', 'payment_gateway_queue');
        $this->addForeignKey('fk-payment_gateway_queue-restaurant_uuid', 'payment_gateway_queue', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', $action);

        $this->dropForeignKey('fk-refund-restaurant_uuid', 'refund');
        $this->addForeignKey('fk-refund-restaurant_uuid', 'refund', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', $action);

        $this->dropForeignKey('fk-restaurant-country_id', 'restaurant');
        $this->addForeignKey(
            'fk-restaurant-country_id',
            'restaurant',
            'country_id',
            'country',
            'country_id',
            $action
        );

        $this->dropForeignKey('fk-restaurant-currency_id', 'restaurant');
        $this->addForeignKey(
            'fk-restaurant-currency_id',
            'restaurant',
            'currency_id',
            'currency',
            'currency_id',
            $action
        );

        $this->dropForeignKey('fk-restaurant_currency-currency_id', 'restaurant_currency');
        $this->addForeignKey(
            'fk-restaurant_currency-currency_id',
            'restaurant_currency',
            'currency_id',
            'currency',
            'currency_id',
            $action
        );

        $this->dropForeignKey('fk-restaurant_currency-restaurant_uuid', 'restaurant_currency');
        $this->addForeignKey(
            'fk-restaurant_currency-restaurant_uuid',
            'restaurant_currency',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            $action
        );


        $this->dropForeignKey('fk-subscription-payment_method_id', 'subscription');
        $this->addForeignKey(
            'fk-subscription-payment_method_id',
            'subscription',
            'payment_method_id',
            'payment_method',
            'payment_method_id',
            $action
        );


        $this->dropForeignKey('fk-subscription-payment_uuid', 'subscription');
        $this->addForeignKey(
            'fk-subscription-payment_uuid',
            'subscription',
            'payment_uuid',
            'subscription_payment',
            'payment_uuid',
            $action
        );

        $this->dropForeignKey('fk-subscription-plan_id', 'subscription');
        $this->addForeignKey(
            'fk-subscription-plan_id', 'subscription', 'plan_id', 'plan', 'plan_id', $action
        );

        $this->dropForeignKey('fk-subscription-restaurant_uuid', 'subscription');
        $this->addForeignKey('fk-subscription-restaurant_uuid', 'subscription', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', $action);


        // add foreign key for table `tap_queue`
        $this->dropForeignKey('fk-tap_queue-restaurant_uuid', 'tap_queue');
        $this->addForeignKey('fk-tap_queue-restaurant_uuid', 'tap_queue', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', $action);

        Yii::$app->db->createCommand('SET foreign_key_checks = 1')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211103_105549_currency_conversion_table cannot be reverted.\n";

        return false;
    }
    */
}
