<?php

use yii\db\Migration;

/**
 * Class m201024_192821_alert_subscription_table
 */
class m201024_192821_alert_subscription_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

      $this->dropForeignKey('fk-subscription_payment-customer_id', 'subscription_payment');


      // add foreign key for `restaurant_uuid` in table `subscription_payment`
      $this->addForeignKey(
          'fk-subscription_payment-restaurant_uuid',
          'subscription_payment',
          'restaurant_uuid',
          'restaurant',
          'restaurant_uuid',
          'CASCADE'
      );


      // creates index for column `subscription_uuid`in table `subscription_payment`
      $this->createIndex(
          'idx-subscription_payment-subscription_uuid',
          'subscription_payment',
          'subscription_uuid'
      );

      // add foreign key for `subscription_uuid` in table `subscription_payment`
      $this->addForeignKey(
          'fk-subscription_payment-subscription_uuid',
          'subscription_payment',
          'subscription_uuid',
          'subscription',
          'subscription_uuid',
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
