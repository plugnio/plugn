<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bank_discount}}`.
 */
class m200919_154201_create_bank_discount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->dropForeignKey('fk-voucher-bank_id', 'voucher');
        $this->dropIndex('idx-voucher-bank_id', 'voucher');

        $this->dropColumn('voucher', 'bank_id');


        $tableOptions = null;
         if ($this->db->driverName === 'mysql') {
             // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
             $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
         }

        $this->createTable('{{%bank_discount}}', [
            'bank_discount_id' => $this->bigPrimaryKey(),
            'bank_id' => $this->integer(),
            'restaurant_uuid' => $this->char(60)->notNull(),
            'discount_type' => $this->smallInteger()->defaultValue(0)->notNull(),
            'discount_amount' => $this->integer()->notNull(),
            'bank_discount_status' => $this->smallInteger()->defaultValue(1), // 1=> active , 2 => expired
            'valid_from' => $this->dateTime(),
            'valid_until' => $this->dateTime(),
            'max_redemption' => $this->integer()->defaultValue(0), //unlimited
            'limit_per_customer' => $this->integer()->defaultValue(0), //unlimited
            'minimum_order_amount' => $this->integer()->defaultValue(0),
            'bank_discount_created_at' => $this->dateTime(),
            'bank_discount_updated_at' => $this->dateTime(),
        ], $tableOptions);

        // creates index for column `bank_id`
        $this->createIndex(
                'idx-bank_discount-bank_id',
                'bank_discount',
                'bank_id'
        );

        // add foreign key for table `bank_discount`
        $this->addForeignKey(
                'fk-bank_discount-bank_id',
                'bank_discount',
                'bank_id',
                'bank',
                'bank_id',
                'CASCADE'
        );

        // creates index for column `restaurant_uuid`
        $this->createIndex(
                'idx-bank_discount-restaurant_uuid',
                'bank_discount',
                'restaurant_uuid'
        );

        // add foreign key for table `bank_discount`
        $this->addForeignKey(
                'fk-bank_discount-restaurant_uuid',
                'bank_discount',
                'restaurant_uuid',
                'restaurant',
                'restaurant_uuid',
                'CASCADE'
        );



        $this->createTable('{{%customer_bank_discount}}', [
            'customer_bank_discount_id' => $this->bigPrimaryKey(),
            'customer_id' => $this->bigInteger(),
            'bank_discount_id' => $this->bigInteger(),
        ],$tableOptions);



        // creates index for column `customer_id`
        $this->createIndex(
                'idx-customer_bank_discount-customer_id',
                'customer_bank_discount',
                'customer_id'
        );

        // add foreign key for table `customer_bank_discount`
        $this->addForeignKey(
                'fk-customer_bank_discount-customer_id',
                'customer_bank_discount',
                'customer_id',
                'customer',
                'customer_id',
                'CASCADE'
        );



        // creates index for column `bank_discount_id`
        $this->createIndex(
                'idx-customer_bank_discount-bank_discount_id',
                'customer_bank_discount',
                'bank_discount_id'
        );

        // add foreign key for table `customer_bank_discount`
        $this->addForeignKey(
                'fk-customer_bank_discount-bank_discount_id',
                'customer_bank_discount',
                'bank_discount_id',
                'bank_discount',
                'bank_discount_id',
                'CASCADE'
        );




    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->addColumn('voucher', 'bank_id' , $this->integer());


         // creates index for column `bank_id`
         $this->createIndex(
                 'idx-voucher-bank_id',
                 'voucher',
                 'bank_id'
         );

         // add foreign key for table `voucher`
         $this->addForeignKey(
                 'fk-voucher-bank_id',
                 'voucher',
                 'bank_id',
                 'bank',
                 'bank_id',
                 'CASCADE'
         );


         $this->dropForeignKey('fk-customer_bank_discount-bank_discount_id', 'customer_bank_discount');
         $this->dropIndex('idx-customer_bank_discount-bank_discount_id', 'customer_bank_discount');


         $this->dropForeignKey('fk-customer_bank_discount-customer_id', 'customer_bank_discount');
         $this->dropIndex('idx-customer_bank_discount-customer_id', 'customer_bank_discount');


         $this->dropTable('{{%customer_bank_discount}}');


         $this->dropForeignKey('fk-bank_discount-bank_id', 'bank_discount');
         $this->dropIndex('idx-bank_discount-bank_id', 'bank_discount');

         $this->dropForeignKey('fk-bank_discount-restaurant_uuid', 'bank_discount');
         $this->dropIndex('idx-bank_discount-restaurant_uuid', 'bank_discount');


        $this->dropTable('{{%bank_discount}}');
    }
}
