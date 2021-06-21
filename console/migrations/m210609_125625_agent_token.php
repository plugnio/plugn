<?php

use yii\db\Migration;

/**
 * Class m210609_125625_agent_token
 */
class m210609_125625_agent_token extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        if ($this->db->getTableSchema('{{%agent_token}}', true) !== null) {
            return true;
        }

        $this->createTable('{{%agent_token}}', [
            'token_uuid' => $this->char(36),
            'agent_id' => $this->bigInteger(20)->notNull(),
            'token_value' => $this->string(255),
            'token_device' => $this->string(255),
            'token_device_id' => $this->string(255),
            'token_status' => $this->smallInteger(6),
            'token_last_used_datetime' => $this->dateTime(),
            'token_expiry_datetime' => $this->dateTime(),
            'token_created_datetime' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'agent_token', 'token_uuid');

        $this->createIndex(
            'idx-agent_token-agent_id', 'agent_token', 'agent_id'
        );

        $this->addForeignKey(
            'fk-agent_token-agent_id', 'agent_token', 'agent_id', 'agent', 'agent_id', 'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-agent_token-agent_id', 'agent_token'
        );

        $this->dropIndex(
            'idx-agent_token-agent_id', 'agent_token'
        );

        $this->dropTable('agent_token');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210609_125625_agent_token cannot be reverted.\n";

        return false;
    }
    */
}
