<?php

use yii\db\Migration;

/**
 * Class m210714_095138_add_delivery_fee_field_to_restaurant_table
 */
class m210714_095138_add_delivery_fee_field_to_restaurant_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->addColumn('restaurant', 'warehouse_delivery_charges', $this->decimal(10,3)->unsigned()->defaultValue(0)->notNull()->after('warehouse_fee'));
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropColumn('restaurant', 'warehouse_delivery_charges');
  }
}
