<?php

use yii\db\Migration;

/**
 * Class m210506_215407_alert_refund_status_field
 */
class m210506_215407_alert_refund_status_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('refund', 'refund_status', $this->string()->defaultValue('Initiated'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('refund', 'refund_status', $this->string()->defaultValue('Pending'));
    }

}
