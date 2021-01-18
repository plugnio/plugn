<?php

use yii\db\Migration;

/**
 * Class m210118_184312_alert_min_order_amount_field_to_voucher_table
 */
class m210118_184312_alert_min_order_amount_field_to_voucher_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('voucher', 'minimum_order_amount',  $this->float()->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('voucher', 'minimum_order_amount',  $this->integer()->defaultValue(0));
    }

}
