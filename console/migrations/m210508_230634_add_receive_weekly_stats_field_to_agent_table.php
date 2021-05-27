<?php

use yii\db\Migration;

/**
 * Class m210508_230634_add_receive_weekly_stats_field_to_agent_table
 */
class m210508_230634_add_receive_weekly_stats_field_to_agent_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->addColumn('agent', 'receive_weekly_stats' , $this->smallInteger()->defaultValue(1));
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropColumn('agent', 'retention_email_sent');
  }
}
