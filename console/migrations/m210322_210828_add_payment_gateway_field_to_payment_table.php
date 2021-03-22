<?php

use yii\db\Migration;

/**
 * Class m210322_210828_add_payment_gateway_field_to_payment_table
 */
class m210322_210828_add_payment_gateway_field_to_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('payment', 'payment_gateway_name' ,  $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('payment', 'payment_gateway_name');
    }

}
