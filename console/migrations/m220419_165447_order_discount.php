<?php

use yii\db\Migration;
use yii\helpers\Console;

/**
 * Class m220419_165447_order_discount
 */
class m220419_165447_order_discount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('order', 'discount_type', $this->decimal(10, 3)->after('voucher_code'));
        $this->addColumn ('order', 'voucher_discount', $this->decimal(10, 3)->after('discount_type'));
        $this->addColumn ('order', 'bank_discount', $this->decimal(10, 3)->after('bank_discount_id'));

        $query = \common\models\Order::find();

        $total = \common\models\Order::find()->count();

        Console::startProgress(0, $total);

        $n = 0;

        foreach ($query->batch(100) as $orders)
        {
            foreach ($orders as $order)
            {
                if ($order->voucher) {
                    $order->discount_type = $order->voucher->discount_type;

                    $order->voucher_discount = $order->voucher->discount_type == 1 ?
                        $order->subtotal * ($order->voucher->discount_amount / 100) : $order->voucher->discount_amount;
                }

                if ($order->bankDiscount) {
                    $order->bank_discount = $order->bankDiscount->discount_type == 1 ?
                        $order->subtotal * ($order->bankDiscount->discount_amount / 100) : $order->bankDiscount->discount_amount;
                }

                $order->save();

                $n++;
            }

            Console::updateProgress($n, $total);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220419_165447_order_discount cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220419_165447_order_discount cannot be reverted.\n";

        return false;
    }
    */
}
