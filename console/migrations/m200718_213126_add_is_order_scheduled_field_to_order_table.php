<?php

use yii\db\Migration;

/**
 * Class m200718_213126_add_is_order_scheduled_field_to_order_table
 */
class m200718_213126_add_is_order_scheduled_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'is_order_scheduled',  $this->smallInteger());
      $this->addColumn('order', 'scheduled_time_start_from',  $this->datetime());
      $this->addColumn('order', 'scheduled_time_to',  $this->datetime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order', 'is_order_scheduled');
      $this->dropColumn('order', 'scheduled_time_start_from');
      $this->dropColumn('order', 'scheduled_time_to');
    }

}
