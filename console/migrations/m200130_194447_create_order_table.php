<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order}}`.
 */
class m200130_194447_create_order_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%customer}}', [
            'customer_id' => $this->bigPrimaryKey(),
            'customer_name' => $this->string()->notNull(),
            'customer_phone_number' => $this->string()->notNull(),
            'customer_email' => $this->string(),
            'customer_created_at' => $this->dateTime()->notNull(),
            'customer_updated_at' => $this->dateTime()->notNull(),
                ], $tableOptions);

        $this->createTable('{{%order}}', [
            'order_id' => $this->bigPrimaryKey(),
            'customer_id' => $this->bigInteger()->notNull(),
            'customer_name' => $this->string(255)->notNull(),
            'customer_phone_number' => $this->string(255)->notNull(),
            'customer_email' => $this->string(255),
            'restaurant_uuid' => $this->char(60),
            'area_id' => $this->integer()->notNull(),
            'area_name' => $this->string(255)->notNull(),
            'area_name_ar' => $this->string(255)->notNull(),
            'unit_type' => $this->string()->notNull(),
            'block' => $this->string()->notNull(),
            'street' => $this->string()->notNull(),
            'avenue' => $this->string(),
            'house_number' => $this->string()->notNull(),
            'special_directions' => $this->string(255),
            'payment_method_id' => $this->integer()->notNull(),
            'payment_method_name' => $this->string()->notNull(),
            'total_price' => $this->float(),
            'total_items_price' => $this->float(),
            'delivery_fee' => $this->float(),
            'order_status' => $this->tinyInteger(1)->defaultValue(1),
            'order_mode' => $this->tinyInteger(1)->notNull(),
            'order_created_at' => $this->dateTime()->notNull(),
            'order_updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);


        // creates index for column `customer_id`
        $this->createIndex(
                'idx-order-customer_id', 'order', 'customer_id'
        );

        // add foreign key for table `order`
        $this->addForeignKey(
                'fk-order-customer_id', 'order', 'customer_id', 'customer', 'customer_id', 'CASCADE'
        );

        // creates index for column `restaurant_uuid`
        $this->createIndex(
                'idx-order-restaurant_uuid', 'order', 'restaurant_uuid'
        );

        // add foreign key for table `order`
        $this->addForeignKey(
                'fk-order-restaurant_uuid', 'order', 'restaurant_uuid', 'restaurant', 'restaurant_uuid', 'CASCADE'
        );

        // creates index for column `area_id`
        $this->createIndex(
                'idx-order-area_id', 'order', 'area_id'
        );

        // add foreign key for table `order`
        $this->addForeignKey(
                'fk-order-area_id', 'order', 'area_id', 'area', 'area_id', 'CASCADE'
        );

        // creates index for column `payment_method_id`
        $this->createIndex(
                'idx-order-payment_method_id', 'order', 'payment_method_id'
        );

        // add foreign key for table `order`
        $this->addForeignKey(
                'fk-order-payment_method_id', 'order', 'payment_method_id', 'payment_method', 'payment_method_id', 'CASCADE'
        );



        $this->createTable('{{%order_item}}', [
            'order_item_id' => $this->bigPrimaryKey(),
            'order_id' => $this->bigInteger()->notNull(),
            'item_uuid' => $this->string(300),
            'item_name' => $this->string(255)->notNull(),
            'item_price' => $this->float()->notNull(),
            'qty' => $this->integer(),
            'instructions' => $this->string(255)
                ], $tableOptions);

        // creates index for column `order_id`
        $this->createIndex(
                'idx-order_item-order_id', 'order_item', 'order_id'
        );

        // add foreign key for table `order`
        $this->addForeignKey(
                'fk-order_item-order_id', 'order_item', 'order_id', 'order', 'order_id', 'CASCADE'
        );

        // creates index for column `item_uuid`
        $this->createIndex(
                'idx-order_item-item_uuid', 'order_item', 'item_uuid'
        );

        // add foreign key for table `item`
        $this->addForeignKey(
                'fk-order_item-item_uuid', 'order_item', 'item_uuid', 'item', 'item_uuid', 'SET NULL', 'SET NULL'
        );

        $this->createTable('{{%order_item_extra_option}}', [
            'order_item_extra_option_id' => $this->bigPrimaryKey()->notNull(),
            'order_item_id' => $this->bigInteger()->notNull(),
            'extra_option_id' => $this->integer(),
            'extra_option_name' => $this->string(255)->notNull(),
            'extra_option_name_ar' => $this->string(255)->notNull()->notNull(),
            'extra_option_price' => $this->float()->notNull(),
                ], $tableOptions);


        // creates index for column `order_item_id`
        $this->createIndex(
                'idx-order_item_extra_option-order_item_id', 'order_item_extra_option', 'order_item_id'
        );

        // add foreign key for table `order_item_extra_option`
        $this->addForeignKey(
                'fk-order_item_extra_option-order_item_id', 'order_item_extra_option', 'order_item_id', 'order_item', 'order_item_id', 'CASCADE'
        );


        // creates index for column `extra_option_id`
        $this->createIndex(
                'idx-order_item_extra_option-extra_option_id', 'order_item_extra_option', 'extra_option_id'
        );

        // add foreign key for table `item`
        $this->addForeignKey(
                'fk-order_item_extra_option-extra_option_id', 'order_item_extra_option', 'extra_option_id', 'extra_option', 'extra_option_id', 'SET NULL', 'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        return true;
    }

}
