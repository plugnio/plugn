<?php

use yii\db\Migration;

/**
 * Class m210208_163412_alert_email_notification_field
 */
class m210208_163412_alert_email_notification_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('agent', 'email_notification',  $this->smallInteger()->defaultValue(1)->after('agent_status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('agent', 'email_notification',  $this->smallInteger()->defaultValue(0)->after('agent_status'));
    }
}
