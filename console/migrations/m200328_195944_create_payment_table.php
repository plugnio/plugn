<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment}}`.
 */
class m200328_195944_create_payment_table extends Migration
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
        $this->createTable('payment', [
            "payment_uuid" => $this->char(36)->notNull(), // used as reference id
            "customer_id" => $this->bigInteger()->notNull(), // Which customer made the payment?
            'order_uuid' => $this->char(40)->notNull(),
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
        $this->addPrimaryKey('PK', 'payment', 'payment_uuid');

        // creates index for column `payment_gateway_order_id`in table `payment`
        $this->createIndex(
            'idx-payment-payment_gateway_order_id',
            'payment',
            'payment_gateway_order_id'
        );
        // creates index for column `payment_gateway_transaction_id`in table `payment`
        $this->createIndex(
            'idx-payment-payment_gateway_transaction_id',
            'payment',
            'payment_gateway_transaction_id'
        );

        // creates index for column `customer_id`in table `payment`
        $this->createIndex(
            'idx-payment-customer_id',
            'payment',
            'customer_id'
        );

        // add foreign key for `customer_id` in table `payment`
        $this->addForeignKey(
            'fk-payment-customer_id',
            'payment',
            'customer_id',
            'customer',
            'customer_id',
            'CASCADE'
        );
        
        $this->createIndex(
            'idx-payment-order_uuid',
            'payment',
            'order_uuid'
        );

        // add foreign key for `order_uuid` in table `payment`
        $this->addForeignKey(
            'fk-payment-order_uuid',
            'payment',
            'order_uuid',
            'order',
            'order_uuid',
            'CASCADE'
        );
        
        
        // Add field to order so each order has a child payment
        $this->addColumn('order', 'payment_uuid', $this->char(36)->after('order_uuid'));
        // creates index for column `payment_uuid`in table `order`
        $this->createIndex(
            'idx-order-payment_uuid',
            'order',
            'payment_uuid'
        );
        // add foreign key for `payment_uuid` in table `order`
        $this->addForeignKey(
            'fk-order-payment_uuid',
            'order',
            'payment_uuid',
            'payment',
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
