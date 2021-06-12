<?php

use yii\db\Migration;

/**
 * Class m210610_114347_add_sender_name_field_to_order_table
 */
class m210610_114347_add_sender_name_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'sender_name' , $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order', 'sender_name');
    }
}
