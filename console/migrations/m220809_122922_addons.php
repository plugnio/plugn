<?php

use yii\db\Migration;

/**
 * Class m220809_122922_addons
 */
class m220809_122922_addons extends Migration
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

        $this->createTable('{{%addon}}', [
            'addon_uuid' => $this->char(60)->unique(),
            'name' => $this->string(100)->notNull(),
            'name_ar' => $this->string(100)->notNull(),
            'description' => $this->text()->notNull(),
            'description_ar' => $this->text(),
            'price' => $this->decimal(10, 3)->notNull(),
            'special_price' => $this->decimal(10, 3),
            'slug' => $this->string(100)->notNull(),
            'expected_delivery' => $this->smallInteger(3)->comment('in days'),//365 days max?
            'sort_number' => $this->integer(11)->unsigned()->defaultValue(0),
            'status' => $this->tinyInteger(2)->defaultValue(10),
            'created_by' => $this->integer(11),//admin_id
            'updated_by' => $this->integer(11),//admin_id
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'addon', 'addon_uuid');

        $this->createIndex(
            'idx-addon-created_by',
            'addon',
            'created_by'
        );

        $this->createIndex(
            'idx-addon-updated_by',
            'addon',
            'updated_by'
        );

        $this->addForeignKey(
            'fk-addon-created_by',
            'addon',
            'created_by',
            'admin',
            'admin_id',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-addon-updated_by',
            'addon',
            'updated_by',
            'admin',
            'admin_id',
            'SET NULL'
        );

        $this->createTable('{{%restaurant_addon}}', [
            'ra_uuid' => $this->char(60)->unique(),
            'addon_uuid' => $this->char(60),
            'restaurant_uuid' => $this->char(60),
            'created_at' => $this->datetime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_addon', 'ra_uuid');

        $this->createIndex(
            'idx-restaurant_addon-restaurant_uuid',
            'restaurant_addon',
            'restaurant_uuid'
        );

        $this->createIndex(
            'idx-restaurant_addon-addon_uuid',
            'restaurant_addon',
            'addon_uuid'
        );

        $this->addForeignKey(
            'fk-restaurant_addon-restaurant_uuid',
            'restaurant_addon',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-restaurant_addon-addon_uuid',
            'restaurant_addon',
            'addon_uuid',
            'addon',
            'addon_uuid',
            'SET NULL'
        );

        $this->createTable('addon_payment', [
            "payment_uuid" => $this->char(60)->notNull(), // used as reference id
            "restaurant_uuid" => $this->char(60)->null(), // Which store made the payment?
            'addon_uuid' => $this->char(60)->null(),
            "payment_gateway_order_id" => $this->string(), // myfatoorah order id
            "payment_gateway_transaction_id" => $this->string(), // myfatoorah transaction id
            "payment_mode" => $this->string(), // which gateway they used
            "payment_current_status" => $this->text(), // Where are we with this payment / result

            // payment amounts
            "payment_amount_charged" => $this->decimal(10, 3)->notNull(), // amount charged to customer
            "payment_net_amount" => $this->decimal(10, 3), // net amount deposited into our account
            "payment_gateway_fee" => $this->decimal(10, 3), // gateway fee charged

            // User defined fields
            "payment_udf1" => $this->string(),
            "payment_udf2" => $this->string(),
            "payment_udf3" => $this->string(),
            "payment_udf4" => $this->string(),
            "payment_udf5" => $this->string(),

            'received_callback' => $this->boolean()->notNull()->defaultValue(0),
            'response_message' => $this->string(),
            'payment_token' => $this->string(),

            //datetime
            'payment_created_at' => $this->dateTime(),
            'payment_updated_at' => $this->dateTime()

        ],$tableOptions);

        $this->addPrimaryKey('PK', 'addon_payment', 'payment_uuid');

        $this->createIndex(
            'idx-addon_payment-restaurant_uuid',
            'addon_payment',
            'restaurant_uuid'
        );

        $this->createIndex(
            'idx-addon_payment-addon_uuid',
            'addon_payment',
            'addon_uuid'
        );

        $this->createIndex(
            'idx-addon_payment-payment_gateway_order_id',
            'addon_payment',
            'payment_gateway_order_id'
        );

        $this->dropIndex(
            'restaurant_uuid',
            'restaurant'
        );

        $this->addForeignKey(
            'fk-addon_payment-restaurant_uuid',
            'addon_payment',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'SET NULL'
        );

        $this->addForeignKey(
            'fk-addon_payment-addon_uuid',
            'addon_payment',
            'addon_uuid',
            'addon',
            'addon_uuid',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220809_122922_addons cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220809_122922_addons cannot be reverted.\n";

        return false;
    }
    */
}
