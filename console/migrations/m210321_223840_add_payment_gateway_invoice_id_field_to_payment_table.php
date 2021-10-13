<?php

use yii\db\Migration;

/**
 * Class m210321_223840_add_payment_gateway_invoice_id_field_to_payment_table
 */
class m210321_223840_add_payment_gateway_invoice_id_field_to_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('payment', 'payment_gateway_invoice_id' ,  $this->string()->defaultValue(NULL)->after('payment_gateway_transaction_id'));
      $this->addColumn('payment', 'payment_gateway_payment_id' ,  $this->string()->defaultValue(NULL)->after('payment_gateway_transaction_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('payment', 'payment_gateway_invoice_id');
      $this->dropColumn('payment', 'payment_gateway_payment_id');
    }

}
