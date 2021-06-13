<?php

use yii\db\Migration;

/**
 * Class m210613_184840_add_armada_order_status_field_to_order_table
 */
class m210613_184840_add_armada_order_status_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'armada_order_status' , $this->string()->after('armada_delivery_code'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order', 'armada_order_status');
    }

}
