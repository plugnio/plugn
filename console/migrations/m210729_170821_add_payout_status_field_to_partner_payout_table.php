<?php

use yii\db\Migration;

/**
 * Class m210729_170821_add_payout_status_field_to_partner_payout_table
 */
class m210729_170821_add_payout_status_field_to_partner_payout_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('partner_payout', 'payout_status', $this->smallInteger()->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('partner_payout', 'payout_status');
    }
}
