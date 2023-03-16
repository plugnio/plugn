<?php

use yii\db\Migration;

/**
 * Class m230219_090316_hotfix_payment
 */
class m230219_090316_hotfix_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $paymentMethods = \agent\models\PaymentMethod::find()->all();

        foreach ($paymentMethods as $paymentMethod)
        {
            \common\models\Order::updateAll([
                    'payment_method_name' => $paymentMethod->payment_method_name,
                    'payment_method_name_ar' => $paymentMethod->payment_method_name_ar,
                ],
                new \yii\db\Expression("payment_method_name IS NULL AND payment_method_id='".$paymentMethod->payment_method_id."'")
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230219_090316_hotfix_payment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230219_090316_hotfix_payment cannot be reverted.\n";

        return false;
    }
    */
}
