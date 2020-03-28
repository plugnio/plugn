<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%agent}}`.
 */
class m200328_170639_add_email_notification_column_to_agent_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agent', 'email_notification', $this->smallInteger()->defaultValue(0)->after('agent_status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('agent', 'email_notification');
    }
}
