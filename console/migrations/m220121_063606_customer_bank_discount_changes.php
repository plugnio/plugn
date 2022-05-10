<?php

use yii\db\Migration;

/**
 * Class m220121_063606_customer_bank_discount_changes
 */
class m220121_063606_customer_bank_discount_changes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        Yii::$app->db->createCommand('SET foreign_key_checks = 0')->execute();

        $this->dropForeignKey('fk-customer_bank_discount-bank_discount_id', 'customer_bank_discount');
        $this->dropForeignKey('fk-customer_bank_discount-customer_id', 'customer_bank_discount');


        $this->addForeignKey(
            'fk-customer_bank_discount-bank_discount_id',
            'customer_bank_discount',
            'bank_discount_id',
            'bank_discount',
            'bank_discount_id'
        );

        $this->addForeignKey(
            'fk-customer_bank_discount-customer_id',
            'customer_bank_discount',
            'customer_id',
            'customer',
            'customer_id'
        );

        Yii::$app->db->createCommand('SET foreign_key_checks = 1')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220121_063606_customer_bank_discount_changes cannot be reverted.\n";

        return false;
    }
    */
}
