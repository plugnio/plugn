<?php

use yii\db\Migration;

/**
 * Class m210324_113153_alert_foreign_keys_in_refund_table
 */
class m210324_113153_alert_foreign_keys_in_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->alterColumn('refunded_item', 'order_item_id', $this->bigInteger());
        $this->alterColumn('refunded_item', 'order_uuid', $this->char(40));

        $this->dropForeignKey('fk-refunded_item-order_uuid', 'refunded_item');

        // add foreign key for table `order`
        $this->addForeignKey(
                'fk-refunded_item-order_uuid', 'refunded_item', 'order_uuid', 'order', 'order_uuid', 'SET NULL','CASCADE'
        );


        $this->dropForeignKey('fk-refunded_item-order_item_id', 'refunded_item');

        // add foreign key for `order_item_id` in table `refunded_item`
        $this->addForeignKey(
                'fk-refunded_item-order_item_id', 'refunded_item', 'order_item_id', 'order_item', 'order_item_id', 'SET NULL','CASCADE'
        );



        $this->alterColumn('refund', 'order_uuid', $this->char(40));

        $this->dropForeignKey('fk-refund-order_uuid', 'refund');

        // add foreign key for `order_uuid` in table `payment`
        $this->addForeignKey(
                'fk-refund-order_uuid', 'refund', 'order_uuid', 'order', 'order_uuid', 'SET NULL','CASCADE'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      return true;
    }

}
