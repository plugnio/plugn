<?php

use yii\db\Migration;

/**
 * Class m210309_170041_alter_orders_foreign_keys
 */
class m210309_170041_alter_orders_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->dropForeignKey('fk-order-area_id', 'order');

      // add foreign key for table `order`
      $this->addForeignKey(
              'fk-order-area_id', 'order', 'area_id', 'area', 'area_id', 'SET NULL' ,'CASCADE'
      );

      $this->dropForeignKey('fk-order-bank_discount_id', 'order');

      // add foreign key for table `order`
      $this->addForeignKey(
              'fk-order-bank_discount_id',
              'order',
              'bank_discount_id',
              'bank_discount',
              'bank_discount_id',
              'SET NULL',
              'CASCADE'
      );

      $this->dropForeignKey('fk-order-delivery_zone_id', 'order');

      // add foreign key for table `order`
      $this->addForeignKey(
              'fk-order-delivery_zone_id',
              'order',
              'delivery_zone_id',
              'delivery_zone',
              'delivery_zone_id',
              'SET NULL',
              'CASCADE'
      );

      $this->dropForeignKey('fk-order-pickup_location_id', 'order');

      // add foreign key for table `order`
      $this->addForeignKey(
              'fk-order-pickup_location_id',
              'order',
              'pickup_location_id',
              'business_location',
              'business_location_id',
              'SET NULL',
              'CASCADE'
      );

      $this->dropForeignKey('fk-order-voucher_id', 'order');

      // add foreign key for table `order`
      $this->addForeignKey(
              'fk-order-voucher_id',
              'order',
              'voucher_id',
              'voucher',
              'voucher_id',
              'SET NULL',
              'CASCADE'
      );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

}
