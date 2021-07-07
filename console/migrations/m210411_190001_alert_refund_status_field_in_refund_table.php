<?php

use yii\db\Migration;

/**
 * Class m210411_190001_alert_refund_status_field_in_refund_table
 */
class m210411_190001_alert_refund_status_field_in_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('refund', 'refund_status', $this->string()->defaultValue('Pending'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('refund', 'refund_status', $this->string());
    }

}
