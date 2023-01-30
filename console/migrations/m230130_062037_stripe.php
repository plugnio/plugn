<?php

use yii\db\Migration;

/**
 * Class m230130_062037_stripe
 */
class m230130_062037_stripe extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $method = new \agent\models\PaymentMethod();
        $method->payment_method_name = "Stripe";
        $method->payment_method_name_ar = "Stripe";
        $method->vat = 0.000;
        $method->source_id = "";
        $method->payment_method_code = "Stripe";
        $method->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230130_062037_stripe cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230130_062037_stripe cannot be reverted.\n";

        return false;
    }
    */
}
