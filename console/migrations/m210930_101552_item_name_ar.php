<?php

use yii\db\Migration;

/**
 * Class m210930_101552_item_name_ar
 */
class m210930_101552_item_name_ar extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('refunded_item', 'item_name_ar', $this->string ()->after('item_name')->null ());
        //$this->addColumn ('order_item', 'item_name_ar', $this->string ()->after('item_name')->null ());

        //update item_name_ar

        $sql = "SELECT * from item where item.item_uuid IN (SELECT DISTINCT item_uuid from refunded_item)";

        $orderedItems = Yii::$app->db->createCommand ($sql)->queryAll ();

        foreach($orderedItems as $orderedItem) {
            /*\common\models\OrderItem::updateAll ([
                'item_name_ar' => $orderedItem->item_name_ar,
            ], [
                'item_uuid' => $orderedItem->item_uuid
            ]);*/

            \common\models\RefundedItem::updateAll ([
                'item_name_ar' => $orderedItem->item_name_ar,
            ], [
                'item_uuid' => $orderedItem->item_uuid
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210930_101552_item_name_ar cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210930_101552_item_name_ar cannot be reverted.\n";

        return false;
    }
    */
}
