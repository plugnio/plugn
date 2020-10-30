<?php

use yii\db\Migration;

/**
 * Class m201028_131103_alert_subscription_status_field
 */
class m201028_131103_alert_subscription_status_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('subscription', 'subscription_status',  $this->tinyInteger(1)->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('subscription', 'subscription_status',  $this->tinyInteger(1)->unsigned()->defaultValue(10));
    }

}
