<?php

use yii\db\Migration;

/**
 * Class m200726_222633_add_armada_delivery_code_field_to_order_table
 */
class m200726_222633_add_armada_delivery_code_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'armada_delivery_code',  $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order', 'armada_delivery_code');
    }
}
