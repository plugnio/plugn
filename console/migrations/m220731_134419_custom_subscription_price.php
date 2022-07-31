<?php

use yii\db\Migration;

/**
 * Class m220731_134419_custom_subscription_price
 */
class m220731_134419_custom_subscription_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'restaurant',
            'custom_subscription_price',
            $this->decimal(10, 3)->after('referral_code')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220731_134419_custom_subscription_price cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220731_134419_custom_subscription_price cannot be reverted.\n";

        return false;
    }
    */
}
