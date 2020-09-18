<?php

use yii\db\Migration;

/**
 * Class m200915_095430_add_credit_card_token_field_to_payment_table
 */
class m200915_095430_add_credit_card_token_field_to_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('payment', 'payment_token', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('payment', 'payment_token'); 
    }
}
