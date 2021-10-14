<?php

use yii\db\Migration;

/**
 * Class m210428_225652_add_payment_method_code_filed_to_payment_method_table
 */
class m210428_225652_add_payment_method_code_filed_to_payment_method_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('payment_method', 'payment_method_code', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('payment_method', 'payment_method_code');
    }
}
