<?php

use yii\db\Migration;

/**
 * Class m230118_072747_invoice_payment
 */
class m230118_072747_invoice_payment extends Migration
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

        $this->createTable('invoice_payment', [
            "payment_uuid" => $this->char(60)->notNull(), // used as reference id
            "restaurant_uuid" => $this->char(60)->null(), // Which store made the payment?
            'invoice_uuid' => $this->char(60)->null(),
            "payment_gateway_transaction_id" => $this->string(), // myfatoorah transaction id
            "payment_mode" => $this->string(), // which gateway they used
            "payment_current_status" => $this->text(), // Where are we with this payment / result
            // payment amounts
            "payment_amount_charged" => $this->decimal(10, 3)->notNull(), // amount charged to customer
            "payment_net_amount" => $this->decimal(10, 3), // net amount deposited into our account
            "payment_gateway_fee" => $this->decimal(10, 3), // gateway fee charged
            "currency_code" => $this->char(3)->defaultValue("KWD"),
            'received_callback' => $this->boolean()->notNull()->defaultValue(0),
            //datetime
            'payment_created_at' => $this->dateTime(),
            'payment_updated_at' => $this->dateTime()

        ], $tableOptions);

        $this->addPrimaryKey('PK', 'invoice_payment', 'payment_uuid');

        $this->createIndex(
            'idx-invoice_payment-restaurant_uuid',
            'invoice_payment',
            'restaurant_uuid'
        );

        $this->addForeignKey(
            'fk-invoice_payment-restaurant_uuid',
            'invoice_payment',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'SET NULL'
        );

        $this->createIndex(
            'idx-invoice_payment-invoice_uuid',
            'invoice_payment',
            'invoice_uuid'
        );

        $this->addForeignKey(
            'fk-invoice_payment-invoice_uuid',
            'invoice_payment',
            'invoice_uuid',
            'restaurant_invoice',
            'invoice_uuid',
            'SET NULL'
        );

        //$this->addColumn('invoice_payment', 'currency_code', $this->char(3)->defaultValue("KWD"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('invoice_payment');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230118_072747_invoice_payment cannot be reverted.\n";

        return false;
    }
    */
}
