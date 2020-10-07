<?php

use yii\db\Migration;

/**
 * Class m201002_212451_add_qty_field_to_order_extra_option_table
 */
class m201002_212451_add_qty_field_to_order_extra_option_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order_item_extra_option', 'qty' , $this->integer());


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order_item_extra_option', 'qty');
    }

}
