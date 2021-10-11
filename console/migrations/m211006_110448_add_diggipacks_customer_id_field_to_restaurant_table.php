<?php

use yii\db\Migration;

/**
 * Class m211006_110448_add_diggipacks_customer_id_field_to_restaurant_table
 */
class m211006_110448_add_diggipacks_customer_id_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn ('business_location', 'diggipack_customer_id', $this->string());
      $this->addColumn ('order', 'diggipack_awb_no', $this->string());
      $this->addColumn ('order', 'diggipack_order_status', $this->char(4));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('business_location', 'diggipack_customer_id');
      $this->dropColumn('order', 'diggipack_awb_no');
      $this->dropColumn('order', 'diggipack_order_status');
    }

}
