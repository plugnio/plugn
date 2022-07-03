<?php

use yii\db\Migration;

/**
 * Class m220225_112755_free_checkout
 */
class m220225_112755_free_checkout extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $model = new \common\models\PaymentMethod;
        $model->payment_method_name = "Free checkout";
        $model->payment_method_name_ar= "الخروج مجانا";
        $model->vat = 0;
        $model->payment_method_code = "free-checkout";
        $model->save();

        \common\models\PaymentMethod::updateAll (['payment_method_code' => 'credit-card'], [
            'payment_method_name' => 'Credit Card'
        ]);

        \common\models\PaymentMethod::updateAll (['payment_method_code' => 'cash'], [
            'payment_method_name' => 'Cash'
        ]);

        \common\models\PaymentMethod::updateAll (['payment_method_code' => 'mada'], [
            'payment_method_name' => 'Mada'
        ]);

        \common\models\PaymentMethod::updateAll (['payment_method_code' => 'benefit'], [
            'payment_method_name' => 'Benefit'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        \common\models\PaymentMethod::deleteAll (['payment_method_code' => 'free-checkout']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220225_112755_free_checkout cannot be reverted.\n";

        return false;
    }
    */
}
