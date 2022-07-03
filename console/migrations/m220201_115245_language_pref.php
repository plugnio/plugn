<?php

use yii\db\Migration;

/**
 * Class m220201_115245_language_pref
 */
class m220201_115245_language_pref extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('agent', 'agent_language_pref', $this->char (2)->null ()->after('reminder_email'));

        $this->addColumn ('agent', 'agent_email_verification', $this->boolean ()->defaultValue (false)->after('agent_email'));

        $this->addColumn ('agent', 'agent_limit_email', $this->dateTime ()->after('agent_email_verification'));

        $this->addColumn ('agent', 'deleted', $this->boolean ()->defaultValue (false) ->after('receive_weekly_stats'));

        \common\models\Agent::updateAll (['agent_email_verification' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220201_115245_language_pref cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220201_115245_language_pref cannot be reverted.\n";

        return false;
    }
    */
}
