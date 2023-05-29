<?php

use yii\db\Migration;

/**
 * Class m230524_093300_voucher_cat
 */
class m230524_093300_voucher_cat extends Migration
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

        $this->addColumn('voucher', 'exclude_discounted_items', $this->boolean()->after('minimum_order_amount')
            ->defaultValue(true));

        $this->createTable('voucher_item', [
            "vi_uuid" => $this->char(60)->notNull(), // used as reference id
            "voucher_id" => $this->bigInteger(20)->notNull(),
            "item_uuid" => $this->string(300)->notNull()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'voucher_item', 'vi_uuid');

        $this->createIndex(
            'idx-voucher_item-voucher_id',
            'voucher_item',
            'voucher_id'
        );

        $this->addForeignKey(
            'fk-voucher_item-voucher_id',
            'voucher_item',
            'voucher_id',
            'voucher',
            'voucher_id'
        );

        $this->createIndex(
            'idx-voucher_item-item_uuid',
            'voucher_item',
            'item_uuid'
        );

        $this->addForeignKey(
            'fk-voucher_item-item_uuid',
            'voucher_item',
            'item_uuid',
            'item',
            'item_uuid'
        );

        //category

        $this->createTable('voucher_category', [
            "vc_uuid" => $this->char(60)->notNull(), // used as reference id
            "voucher_id" => $this->bigInteger(20)->notNull(),
            "category_id" => $this->integer(11)->notNull()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'voucher_category', 'vc_uuid');

        $this->createIndex(
            'idx-voucher_category-voucher_id',
            'voucher_category',
            'voucher_id'
        );

        $this->addForeignKey(
            'fk-voucher_category-voucher_id',
            'voucher_category',
            'voucher_id',
            'voucher',
            'voucher_id'
        );

        $this->createIndex(
            'idx-voucher_category-category_id',
            'voucher_category',
            'category_id'
        );

        $this->addForeignKey(
            'fk-voucher_category-category_id',
            'voucher_category',
            'category_id',
            'category',
            'category_id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230524_093300_voucher_cat cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230524_093300_voucher_cat cannot be reverted.\n";

        return false;
    }
    */
}
