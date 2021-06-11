<?php

use yii\db\Migration;

/**
 * Class m210611_074051_agent_token
 */
class m210611_074051_agent_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn ('agent_token', 'token_uuid', $this->string (60));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210611_074051_agent_token cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210611_074051_agent_token cannot be reverted.\n";

        return false;
    }
    */
}
