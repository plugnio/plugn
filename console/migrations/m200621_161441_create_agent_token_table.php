<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%agent_token}}`.
 */
class m200621_161441_create_agent_token_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        //agent token 
        Yii::$app->db->createCommand('SET foreign_key_checks = 0')->execute();

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%agent_token}}', [
            'token_uuid' => $this->char(36),
            'agent_id' => $this->bigInteger()->notNull(),
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

        Yii::$app->db->createCommand('SET foreign_key_checks = 1')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%agent_token}}');
    }

}
