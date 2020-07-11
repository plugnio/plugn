<?php

use yii\db\Migration;

/**
 * Class m200710_154139_add_schedule_order_field_to_restaurant_table
 */
class m200710_154139_add_schedule_order_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'schedule_order',  $this->smallInteger()->defaultValue(0));
      $this->addColumn('restaurant', 'schedule_interval',  $this->smallInteger()->defaultValue(60));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'schedule_order');
      $this->dropColumn('restaurant', 'schedule_interval');
    }
}
