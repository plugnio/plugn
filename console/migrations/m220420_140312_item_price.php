<?php

use yii\db\Migration;

/**
 * Class m220420_140312_item_price
 */
class m220420_140312_item_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('order_item', 'item_unit_price', $this->decimal (10, 3)
            ->after('item_name_ar'));

        $query = \common\models\OrderItem::find();

        foreach ($query->batch (100) as $orderItems)
        {
            foreach ($orderItems as $orderItem)
            {
                \common\models\OrderItem::updateAll ([
                    'item_unit_price' => $orderItem->item_price/ $orderItem->qty
                ], [
                    'order_item_id' => $orderItem->order_item_id
                ]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220420_140312_item_price cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220420_140312_item_price cannot be reverted.\n";

        return false;
    }
    */
}
