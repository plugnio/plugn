<?php

use yii\db\Migration;

/**
 * Class m210202_173657_add_warehouse_fee_field_to_restaurant_table
 */
class m210202_173657_add_warehouse_fee_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'warehouse_fee', $this->decimal(10,3)->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'warehouse_fee');
    }
}
