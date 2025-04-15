<?php

use yii\db\Migration;

/**
 * Class m250415_012131_cart
 */
class m250415_012131_cart extends Migration
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

        $this->createTable('cart', [
            'cart_uuid' => $this->char(60)->notNull(),
            'customer_id' => $this->bigInteger(20)->null(),
            'session_id' => $this->string()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-cart',
            'cart',
            'cart_uuid');

        $this->createIndex('idx-cart-session_id',
            'cart',
            'session_id');

        $this->createIndex('idx-cart-customer_id',
            'cart',
            'customer_id');

        $this->addForeignKey('fk-cart-customer_id',
            'cart',
            'customer_id',
            'customer',
            'customer_id');

        $this->createTable('cart_item', [
            "cart_item_uuid"=> $this->char(60)->notNull(),
            'cart_uuid' => $this->char(60)->notNull(),
            'item_uuid' => $this->char(60)->notNull(),
            'item_variant_uuid' => $this->char(60)->null(),
            'key' => $this->string()->notNull(),
            "qty" => $this->integer(11)->notNull()->defaultValue(1),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-cart_item',
            'cart_item',
            'cart_item_uuid');

        $this->createIndex('idx-cart_item-cart_uuid',
            'cart_item',
            'cart_uuid');

        $this->addForeignKey('fk-cart_item-cart_uuid',
            'cart_item',
            'cart_uuid',
            'cart',
            'cart_uuid');

        $this->createIndex('idx-cart_item-item_uuid',
            'cart_item',
            'item_uuid');

        $this->addForeignKey('fk-cart_item-item_uuid',
            'cart_item',
            'item_uuid',
            'item',
            'item_uuid');

        $this->createIndex('idx-cart_item-item_variant_uuid',
            'cart_item',
            'item_variant_uuid');

        $this->addForeignKey('fk-cart_item-item_variant_uuid',
            'cart_item',
            'item_variant_uuid',
            'item_variant',
            'item_variant_uuid');

        $this->createTable('cart_item_option', [
            "cart_item_option_uuid"=> $this->char(60)->notNull(),
            'cart_item_uuid' => $this->char(60)->notNull(),
            'option_id' => $this->integer(11)->notNull(),
            'value_id' => $this->integer(11)->notNull(),
            "value" => $this->string()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-cart_item_option',
            'cart_item_option',
            'cart_item_option_uuid');

        $this->createIndex('idx-cart_item_option-cart_item_uuid',
            'cart_item_option',
            'cart_item_uuid');

        $this->addForeignKey('fk-cart_item_option-cart_item_uuid',
            'cart_item_option',
            'cart_item_uuid',
            'cart_item',
            'cart_item_uuid');

        $this->createIndex('idx-cart_item_option-option_id',
            'cart_item_option',
            'option_id');

        $this->addForeignKey('fk-cart_item_option-option_id',
            'cart_item_option',
            'option_id',
            'option',
            'option_id');

        $this->createIndex('idx-cart_item_option-value_id',
            'cart_item_option',
            'value_id');

        $this->addForeignKey('fk-cart_item_option-value_id',
            'cart_item_option',
            'value_id',
            'extra_option',
            'extra_option_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cart_item_option');
        $this->dropTable('cart_item');
        $this->dropTable('{{%cart}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250415_012131_cart cannot be reverted.\n";

        return false;
    }
    */
}
