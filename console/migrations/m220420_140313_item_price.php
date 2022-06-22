<?php

use yii\db\Migration;
use yii\helpers\Console;

/**
 * Class m220420_140313_item_price
 */
class m220420_140313_item_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = $this
            ->getDb()
            ->getSchema()
            ->getTableSchema('order_item');

        if (!isset($table->columns['item_unit_price'])) {
            $this->addColumn('order_item', 'item_unit_price', $this->decimal(10, 3)
                ->after('item_name_ar'));
        }

        /*$total = \common\models\OrderItem::find()
            ->andWhere(new \yii\db\Expression('item_unit_price IS NULL or item_unit_price=0'))
            ->count();

        Console::startProgress(0, $total);

        $query = \common\models\OrderItem::find()
            ->andWhere(new \yii\db\Expression('item_unit_price IS NULL or item_unit_price=0'));

        $n = 0;

        foreach ($query->batch (100) as $orderItems)
        {
            foreach ($orderItems as $orderItem)
            {
                \common\models\OrderItem::updateAll ([
                    'item_unit_price' => $orderItem->item_price/ $orderItem->qty
                ], [
                    'order_item_id' => $orderItem->order_item_id
                ]);

                $n++;
            }
        }

        Console::updateProgress($n, $total);*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220420_140313_item_price cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220420_140313_item_price cannot be reverted.\n";

        return false;
    }
    */
}
