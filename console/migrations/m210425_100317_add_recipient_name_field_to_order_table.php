<?php

use yii\db\Migration;

/**
 * Class m210425_100317_add_recipient_name_field_to_order_table
 */
class m210425_100317_add_recipient_name_field_to_order_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->addColumn('restaurant', 'enable_gift_message' , $this->smallInteger()->defaultValue(0));
    $this->addColumn('order', 'recipient_name' , $this->string());
    $this->addColumn('order', 'recipient_phone_number' , $this->string());
    $this->addColumn('order', 'gift_message' , $this->string());
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropColumn('restaurant', 'enable_gift_message');
    $this->dropColumn('order', 'recipient_name');
    $this->dropColumn('order', 'recipient_phone_number');
    $this->dropColumn('order', 'gift_message');
  }
}
