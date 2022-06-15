<?php

use yii\db\Migration;

/**
 * Class m210915_123343_add_transfer_file_field_to_partner_payout_table
 */
class m210915_123343_add_transfer_file_field_to_partner_payout_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('partner_payout', 'transfer_file', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('partner_payout', 'transfer_file');
    }


}
