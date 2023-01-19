<?php

use yii\db\Migration;

/**
 * Class m230117_082451_invoice
 */
class m230117_082451_invoice extends Migration
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

        $this->createTable('{{%restaurant_invoice}}', [
            'invoice_uuid' => $this->string(60),
            'invoice_number' => $this->char(60)->notNull(),
            'restaurant_uuid' => $this->char(60)->notNull(),
            'order_uuid'=> $this->char(40),
            'payment_uuid' => $this->char(60),
            'amount'=> $this->decimal(10, 3)->notNull(),
            'currency_code' => $this->char(3)->defaultValue('KWD'),
            'invoice_status' => $this->boolean()->defaultValue(false),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_invoice', 'invoice_uuid');

        $this->createIndex(
            'idx-restaurant_invoice-invoice_number', 'restaurant_invoice', 'invoice_number'
        );

        $this->createIndex(
            'idx-restaurant_invoice-restaurant_uuid', 'restaurant_invoice', 'restaurant_uuid'
        );

        $this->createIndex(
            'idx-restaurant_invoice-order_uuid', 'restaurant_invoice', 'order_uuid'
        );

        $this->createIndex(
            'idx-restaurant_invoice-payment_uuid', 'restaurant_invoice', 'payment_uuid'
        );

        //restaurant_uuid

        $this->addForeignKey(
            'fk-restaurant_invoice-restaurant_uuid', 'restaurant_invoice', 'restaurant_uuid',
            'restaurant', 'restaurant_uuid'
        );

        //order_uuid

        $this->addForeignKey(
            'fk-restaurant_invoice-order_uuid', 'restaurant_invoice', 'order_uuid',
            'order', 'order_uuid'
        );

        //payment_uuid

        $this->addForeignKey(
            'fk-restaurant_invoice-payment_uuid', 'restaurant_invoice', 'payment_uuid',
            'payment', 'payment_uuid'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%restaurant_invoice}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230117_082451_invoice cannot be reverted.\n";

        return false;
    }
    */
}
