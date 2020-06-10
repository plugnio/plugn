<?php

use yii\db\Migration;

/**
 * Class m200610_120032_add_unit_sold_field_to_item_table
 */
class m200610_120032_add_unit_sold_field_to_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->alterColumn('item', 'stock_qty', $this->integer()->unsigned()->defaultValue(0));
         $this->addColumn('item','unit_sold', $this->integer()->unsigned()->defaultValue(0)->after('stock_qty'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
         $this->dropColumn('item','unit_sold');
    }
}
