<?php

use yii\db\Migration;

/**
 * Class m230827_132656_campaign
 */
class m230827_132656_campaign extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agent', 'utm_uuid', $this->char(64)->after('agent_id'));

        $this->createIndex('ind-agent-utm_uuid', 'agent', 'utm_uuid');

        $this->addForeignKey(
            'fk-agent-utm_uuid', 'agent', 'utm_uuid',
            'campaign', 'utm_uuid', "CASCADE"
        );

        $this->addColumn('campaign', 'no_of_signups', $this->integer(11)->after('investment'));
        $this->addColumn('campaign', 'no_of_clicks', $this->integer(11)->after('no_of_signups'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230827_132656_campaign cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230827_132656_campaign cannot be reverted.\n";

        return false;
    }
    */
}
