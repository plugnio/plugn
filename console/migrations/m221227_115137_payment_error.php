<?php

use yii\db\Migration;

/**
 * Class m221227_115137_payment_error
 */
class m221227_115137_payment_error extends Migration
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

        $this->createTable('{{%payment_failed}}', [
            'payment_failed_uuid' => $this->char(60),
            'payment_uuid' => $this->char(60)->notNull(),
            'order_uuid' => $this->char(40),
            'customer_id' => $this->bigInteger(20),
            'response' => $this->text(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'payment_failed', 'payment_failed_uuid');

        $this->createIndex(
            'idx-payment_failed-order_uuid',
            'payment_failed',
            'order_uuid'
        );

        $this->addForeignKey(
            'fk-payment_failed-order_uuid',
            'payment_failed',
            'order_uuid',
            'order',
            'order_uuid',
            'SET NULL'
        );

        $this->createIndex(
            'idx-payment_failed-customer_id',
            'payment_failed',
            'customer_id'
        );

        $this->addForeignKey(
            'fk-payment_failed-customer_id',
            'payment_failed',
            'customer_id',
            'customer',
            'customer_id',
            'SET NULL'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment_failed}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221227_115137_payment_error cannot be reverted.\n";

        return false;
    }
    */
}
