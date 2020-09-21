<?php

use yii\db\Migration;

/**
 * Class m200921_112820_add_mashkor_tracking_link_field_to_restaurant_table
 */
class m200921_112820_add_mashkor_tracking_link_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'mashkor_tracking_link', $this->string());
      $this->addColumn('order', 'mashkor_driver_name', $this->string());
      $this->addColumn('order', 'mashkor_driver_phone', $this->string());
      $this->addColumn('order', 'mashkor_order_status', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order', 'mashkor_tracking_link');
      $this->dropColumn('order', 'mashkor_driver_name');
      $this->dropColumn('order', 'mashkor_driver_phone');
      $this->dropColumn('order', 'mashkor_order_status');
    }
}
