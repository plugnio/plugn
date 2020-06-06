<?php

use yii\db\Migration;

/**
 * Class m200605_162826_add_new_fileds_to_order_table
 */
class m200605_162826_add_new_fileds_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('order', 'order_status', $this->tinyInteger(1)->unsigned()->defaultValue(9)); //by default it will be Abandoned checkout

        $this->renameColumn('order', 'total_items_price', 'subtotal');

        $this->addColumn('order', 'subtotal_before_refund', $this->float()->unsigned()->defaultValue(0)->after('subtotal'));
        $this->addColumn('order', 'total_price_before_refund', $this->float()->unsigned()->defaultValue(0)->after('subtotal_before_refund'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('order', 'order_status', $this->tinyInteger(1)->unsigned()->defaultValue(0)); //by default it will be Abandoned checkout


        $this->dropColumn('order', 'subtotal_before_refund');
        $this->dropColumn('order', 'total_price_before_refund');

        $this->renameColumn('order', 'subtotal', 'total_items_price');
    }
}
