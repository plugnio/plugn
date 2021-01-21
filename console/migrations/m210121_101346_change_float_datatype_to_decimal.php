<?php

use yii\db\Migration;

/**
 * Class m210121_101346_change_float_datatype_to_decimal
 */
class m210121_101346_change_float_datatype_to_decimal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('delivery_zone', 'delivery_fee', $this->decimal(10,3)->unsigned()->defaultValue(0));
      $this->alterColumn('delivery_zone', 'min_charge', $this->decimal(10,3)->unsigned()->defaultValue(0));


      $this->alterColumn('order', 'total_price', $this->decimal(10,3)->unsigned()->defaultValue(0));
      $this->alterColumn('order', 'subtotal', $this->decimal(10,3)->unsigned()->defaultValue(0));
      $this->alterColumn('order', 'subtotal_before_refund', $this->decimal(10,3)->unsigned()->defaultValue(0));
      $this->alterColumn('order', 'total_price_before_refund', $this->decimal(10,3)->unsigned()->defaultValue(0));
      $this->alterColumn('order', 'delivery_fee', $this->decimal(10,3)->unsigned()->defaultValue(0));



      $this->alterColumn('order_item', 'item_price', $this->decimal(10,3)->unsigned()->notNull());
      $this->alterColumn('order_item_extra_option', 'extra_option_price', $this->decimal(10,3)->unsigned()->notNull());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('delivery_zone', 'delivery_fee', $this->float()->unsigned());
      $this->alterColumn('delivery_zone', 'min_charge', $this->float()->unsigned());



      $this->alterColumn('order', 'total_price',  $this->float()->unsigned()->defaultValue(0));
      $this->alterColumn('order', 'subtotal',  $this->float()->unsigned()->defaultValue(0));
      $this->alterColumn('order', 'subtotal_before_refund',  $this->float()->unsigned()->defaultValue(0));
      $this->alterColumn('order', 'total_price_before_refund',  $this->float()->unsigned()->defaultValue(0));
      $this->alterColumn('order', 'delivery_fee',  $this->float()->unsigned()->defaultValue(0));

      $this->alterColumn('order_item', 'item_price',  $this->float()->notNull());

      $this->alterColumn('order_item_extra_option', 'extra_option_price',  $this->float()->notNull());


    }

}
