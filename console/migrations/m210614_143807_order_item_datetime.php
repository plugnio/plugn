<?php

use yii\db\Migration;

/**
 * Class m210614_143807_order_item_datetime
 */
class m210614_143807_order_item_datetime extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('order_item', 'order_item_created_at', $this->dateTime ()->after ('customer_instruction'));
        $this->addColumn ('order_item', 'order_item_updated_at', $this->dateTime ()->after ('order_item_created_at'));

        $orders = \common\models\Order::find()->all();

        foreach($orders as $order) {
            \common\models\OrderItem::updateAll ([
                'order_item_created_at' => $order->order_created_at,
                'order_item_updated_at' => $order->order_updated_at
            ], [
                'order_uuid' => $order->order_uuid
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210614_143807_order_item_datetime cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210614_143807_order_item_datetime cannot be reverted.\n";

        return false;
    }
    */
}
