<?php

use yii\db\Migration;

/**
 * Class m201119_172315_add_plugn_fee_field_to_payment_table
 */
class m201119_172315_add_plugn_fee_field_to_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('payment', 'plugn_fee', $this->double(10,3)->after('payment_gateway_fee'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('payment', 'plugn_fee');
    }

}
