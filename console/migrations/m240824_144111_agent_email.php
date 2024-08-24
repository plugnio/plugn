<?php

use yii\db\Migration;

/**
 * Class m240824_144111_agent_email
 */
class m240824_144111_agent_email extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn("agent",  'agent_email', $this->string()->notNull());

        $this->dropIndex("agent_email", "agent");

        $this->createIndex("ind-agent_email", "agent", "agent_email", false);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240824_144111_agent_email cannot be reverted.\n";

        return false;
    }
    */
}
