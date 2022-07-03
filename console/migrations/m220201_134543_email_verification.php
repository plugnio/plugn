<?php

use yii\db\Migration;

/**
 * Class m220201_134543_email_verification
 */
class m220201_134543_email_verification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('agent', 'agent_new_email', $this->string (100)->after('agent_email'));

        $this->addColumn ('customer', 'customer_new_email', $this->string (100)->after('customer_email'));

        $this->addColumn ('customer', 'customer_language_pref', $this->char (2)->null ()->after('customer_new_email'));

        $this->addColumn ('customer', 'customer_email_verification', $this->boolean ()->defaultValue (false)->after('customer_email'));

        $this->addColumn ('customer', 'customer_limit_email', $this->dateTime ()->after('customer_email_verification'));

        $this->addColumn ('customer', 'deleted', $this->boolean ()->defaultValue (false) ->after('customer_updated_at'));

        $this->addColumn ('customer', 'customer_auth_key', $this->string (32)->after('customer_limit_email'));

        \common\models\Customer::updateAll (['customer_email_verification' => true]);

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%customer_email_verify_attempt}}', [
            'ceva_uuid' => $this->char(60),
            'customer_email' => $this->string(50),
            'code' => $this->string(32),
            'ip_address' => $this->string(45),
            'created_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'customer_email_verify_attempt', 'ceva_uuid');

        $this->createTable('{{%agent_email_verify_attempt}}', [
            'aeva_uuid' => $this->char(60),
            'agent_email' => $this->string(50),
            'code' => $this->string(32),
            'ip_address' => $this->string(45),
            'created_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'agent_email_verify_attempt', 'aeva_uuid');
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
        echo "m220201_134543_email_verification cannot be reverted.\n";

        return false;
    }
    */
}
