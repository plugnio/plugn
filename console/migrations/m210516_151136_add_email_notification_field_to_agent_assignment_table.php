<?php

use yii\db\Migration;

/**
 * Class m210516_151136_add_email_notification_field_to_agent_assignment_table
 */
class m210516_151136_add_email_notification_field_to_agent_assignment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('agent_assignment', 'email_notification', $this->smallInteger()->defaultValue(0));
      $this->addColumn('agent_assignment', 'reminder_email',  $this->smallInteger()->defaultValue(0)->after('email_notification') );
      $this->addColumn('agent_assignment', 'receive_weekly_stats' , $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('agent_assignment', 'email_notification');
      $this->dropColumn('agent_assignment', 'reminder_email');
      $this->dropColumn('agent_assignment', 'receive_weekly_stats');
    }

}
