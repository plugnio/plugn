<?php

use yii\db\Migration;

/**
 * Class m210816_140328_order_item
 */
class m210816_140328_order_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $orders = Yii::$app->db->createCommand('select order_uuid,restaurant_uuid from `order`')->queryAll();

        foreach($orders as $order) {
            Yii::$app->db->createCommand('UPDATE `order_item` set restaurant_uuid="'.$order['restaurant_uuid'].'" 
                where order_uuid="'.$order['order_uuid'].'"')->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210816_140328_order_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210816_140328_order_item cannot be reverted.\n";

        return false;
    }
    */
}
