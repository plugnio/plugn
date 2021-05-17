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
      $this->addColumn('agent_assignment', 'business_location_id', $this->bigInteger()->after('agent_id'));



        // creates index for column `business_location_id`
        $this->createIndex(
                'idx-agent_assignment-business_location_id',
                'agent_assignment',
                'business_location_id'
        );


        // add foreign key for table `agent_assignment`
        $this->addForeignKey(
                'fk-agent_assignment-business_location_id',
                'agent_assignment',
                'business_location_id',
                'business_location',
                'business_location_id',
                'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

      $this->dropForeignKey('fk-agent_assignment-business_location_id', 'agent_assignment');
      $this->dropIndex('idx-agent_assignment-business_location_id', 'agent_assignment');

      $this->dropColumn('agent_assignment', 'email_notification');
      $this->dropColumn('agent_assignment', 'reminder_email');
      $this->dropColumn('agent_assignment', 'receive_weekly_stats');
      $this->dropColumn('agent_assignment', 'business_location_id');
    }

}
