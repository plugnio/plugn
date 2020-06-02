<?php

use yii\db\Migration;

/**
 * Class m200602_120742_add_refunded_amount_field_to_order_table
 */
class m200602_120742_add_refunded_amount_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order','refunded_amount', $this->float()->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order','refunded_amount');
    }
}
