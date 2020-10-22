<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subscription_payment}}`.
 */
class m201022_210253_create_subscription_payment_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
      $tableOptions = null;
      if ($this->db->driverName === 'mysql') {
          // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
          $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
      }


      Yii::$app->db->createCommand('SET foreign_key_checks = 0')->execute();

      // Create table that will store payment records
      $this->createTable('subscription_payment', [
          "payment_uuid" => $this->char(36)->notNull(), // used as reference id
          "restaurant_uuid" => $this->char(60)->notNull(), // Which store made the payment?
          'subscription_uuid' => $this->char(60)->notNull(),
          "payment_gateway_order_id" => $this->string(), // myfatoorah order id
          "payment_gateway_transaction_id" => $this->string(), // myfatoorah transaction id
          "payment_mode" => $this->string(), // which gateway they used
          "payment_current_status" => $this->text(), // Where are we with this payment / result

          // payment amounts
          "payment_amount_charged" => $this->double(10,3)->notNull(), // amount charged to customer
          "payment_net_amount" => $this->double(10,3), // net amount deposited into our account
          "payment_gateway_fee" => $this->double(10,3), // gateway fee charged

          // User defined fields
          "payment_udf1" => $this->string(),
          "payment_udf2" => $this->string(),
          "payment_udf3" => $this->string(),
          "payment_udf4" => $this->string(),
          "payment_udf5" => $this->string(),

          //datetime
          'payment_created_at' => $this->dateTime(),
          'payment_updated_at' => $this->dateTime()

      ],$tableOptions);
      $this->addPrimaryKey('PK', 'subscription_payment', 'payment_uuid');

      // creates index for column `payment_gateway_order_id`in table `subscription_payment`
      $this->createIndex(
          'idx-subscription_payment-payment_gateway_order_id',
          'subscription_payment',
          'payment_gateway_order_id'
      );
      // creates index for column `payment_gateway_transaction_id`in table `subscription_payment`
      $this->createIndex(
          'idx-subscription_payment-payment_gateway_transaction_id',
          'subscription_payment',
          'payment_gateway_transaction_id'
      );

      // creates index for column `customer_id`in table `subscription_payment`
      $this->createIndex(
          'idx-subscription_payment-restaurant_uuid',
          'subscription_payment',
          'restaurant_uuid'
      );

      // add foreign key for `customer_id` in table `subscription_payment`
      $this->addForeignKey(
          'fk-subscription_payment-customer_id',
          'subscription_payment',
          'restaurant_uuid',
          'restaurant',
          'restaurant_uuid',
          'CASCADE'
      );

      // Add field to order so each order has a child payment
      $this->addColumn('subscription', 'payment_uuid', $this->char(36)->after('subscription_uuid'));
      // creates index for column `payment_uuid`in table `order`
      $this->createIndex(
          'idx-subscription-payment_uuid',
          'subscription',
          'payment_uuid'
      );
      // add foreign key for `payment_uuid` in table `subscription`
      $this->addForeignKey(
          'fk-subscription-payment_uuid',
          'subscription',
          'payment_uuid',
          'subscription_payment',
          'payment_uuid',
          'CASCADE'
      );


      Yii::$app->db->createCommand('SET foreign_key_checks = 1')->execute();
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
//        $this->dropTable('{{%payment}}');
      return true;
  }
}
