<?php

use yii\db\Migration;

/**
 * Class m230622_123518_auth
 */
class m230622_123518_auth extends Migration
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

        $this->createTable('{{%customer_token}}', [
            'token_uuid' => $this->char(60),
            'customer_id' => $this->bigInteger()->notNull(),
            'token_value' => $this->string(255),
            'token_device' => $this->string(255),
            'token_device_id' => $this->string(255),
            'token_status' => $this->smallInteger(6),
            'token_last_used_datetime' => $this->dateTime(),
            'token_expiry_datetime' => $this->dateTime(),
            'token_created_datetime' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'customer_token', 'token_uuid');

        $this->createIndex(
            'idx-customer_token-agent_id', 'customer_token', 'customer_id'
        );

        $this->addForeignKey(
            'fk-customer_token-agent_id', 'customer_token', 'customer_id', 'customer', 'customer_id', 'CASCADE'
        );

        $this->addColumn(
            'customer',
            'customer_password_hash',
            $this->string()->after('customer_auth_key'));

        $this->addColumn(
            'customer',
            'customer_password_reset_token',
            $this->string()->after('customer_password_hash'));

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
        echo "m230622_123518_auth cannot be reverted.\n";

        return false;
    }
    */
}
