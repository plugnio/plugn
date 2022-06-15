<?php

use yii\db\Migration;

/**
 * Class m220404_201523_add_demand_delivery_field_to_restaurant_table
 */
class m220404_201523_add_demand_delivery_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'demand_delivery', $this->smallInteger()->defaultValue(1));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'demand_delivery');
    }

}
