<?php

use yii\db\Migration;

/**
 * Class m200930_161744_add_reminder_email_field_to_agent_table
 */
class m200930_161744_add_reminder_email_field_to_agent_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('agent', 'reminder_email',  $this->smallInteger()->defaultValue(0)->after('email_notification') );
      $this->addColumn('order', 'reminder_sent', $this->boolean()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('agent', 'reminder_email');
      $this->dropColumn('order', 'reminder_sent');
    }

}
