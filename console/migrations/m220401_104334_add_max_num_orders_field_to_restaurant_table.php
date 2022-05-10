<?php

use yii\db\Migration;

/**
 * Class m220401_104334_add_max_num_orders_field_to_restaurant_table
 */
class m220401_104334_add_max_num_orders_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn ('business_location', 'max_num_orders', $this->integer()->null());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('business_location', 'max_num_orders');
    }
}
