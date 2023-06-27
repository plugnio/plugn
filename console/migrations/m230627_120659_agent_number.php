<?php

use yii\db\Migration;

/**
 * Class m230627_120659_agent_number
 */
class m230627_120659_agent_number extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agent', 'agent_number', $this->string(20)->after('agent_email'));
        $this->addColumn('agent', 'agent_phone_country_code', $this->integer(3)->after('agent_number'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230627_120659_agent_number cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230627_120659_agent_number cannot be reverted.\n";

        return false;
    }
    */
}
