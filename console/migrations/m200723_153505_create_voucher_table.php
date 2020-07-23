<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%voucher}}`.
 */
class m200723_153505_create_voucher_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {

       $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%voucher}}', [
            'voucher_id' => $this->bigPrimaryKey(),
            'restaurant_uuid' => $this->char(60)->notNull(),
            'title' => $this->string()->notNull(),
            'title_ar' => $this->string()->notNull(),
            'code' => $this->string()->notNull(),
            'discount_type' => $this->smallInteger()->defaultValue(0)->notNull(),
            'discount_amount' => $this->integer()->notNull(),
            'voucher_status' => $this->smallInteger()->defaultValue(1), // 1=> active , 2 => expired
            'valid_from' => $this->dateTime(),
            'valid_until' => $this->dateTime(),
            'max_redemption' => $this->integer()->defaultValue(0), //unlimited
            'limit_per_customer' => $this->integer()->defaultValue(0), //unlimited
            'minimum_order_amount' => $this->integer()->defaultValue(0),
            'voucher_created_at' => $this->dateTime(),
            'voucher_updated_at' => $this->dateTime(),
        ],$tableOptions);


        // creates index for column `restaurant_uuid`
        $this->createIndex(
                'idx-voucher-restaurant_uuid',
                'voucher',
                'restaurant_uuid'
        );

        // add foreign key for table `voucher`
        $this->addForeignKey(
                'fk-voucher-restaurant_uuid',
                'voucher',
                'restaurant_uuid',
                'restaurant',
                'restaurant_uuid',
                'CASCADE'
        );


        $this->addColumn('order', 'voucher_id', $this->bigInteger());

        // creates index for column `voucher_id`
        $this->createIndex(
                'idx-order-voucher_id',
                'order',
                'voucher_id'
        );

        // add foreign key for table `order`
        $this->addForeignKey(
                'fk-order-voucher_id',
                'order',
                'voucher_id',
                'voucher',
                'voucher_id',
                'CASCADE'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {

        $this->dropForeignKey('fk-order-voucher_id', 'order');
        $this->dropIndex('idx-order-voucher_id', 'order');

        $this->dropColumn('order', 'voucher_id');

        $this->dropForeignKey('fk-voucher-restaurant_uuid', 'voucher');
        $this->dropIndex('idx-voucher-restaurant_uuid', 'voucher');

        $this->dropTable('{{%voucher}}');
    }

}
