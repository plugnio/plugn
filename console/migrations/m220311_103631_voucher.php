<?php

use yii\db\Migration;
use agent\models\Order;

/**
 * Class m220311_103631_voucher
 */
class m220311_103631_voucher extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //$this->addColumn('order', 'voucher_code', $this->string(100)->after('voucher_id')->null());

        $query = Order::find()
            ->joinWith('voucher');

        foreach ($query->batch() as $rows) {

            foreach ($rows as $order) {

                if(!$order->voucher) {
                    continue;
                }

                Order::updateAll(['voucher_code' => $order->voucher->code], [
                    'order_uuid' => $order->order_uuid
                ]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220311_103631_voucher cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220311_103631_voucher cannot be reverted.\n";

        return false;
    }
    */
}
