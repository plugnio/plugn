<?php

use yii\db\Migration;

/**
 * Class m250327_155428_store_domain_subscription_payment
 */
class m250327_155428_store_domain_subscription_payment extends Migration
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

        $this->createTable('store_domain_subscription_payment', [
            'store_domain_subscription_payment_uuid' => $this->char(60)->notNull(),
            'subscription_uuid' => $this->char(60)->notNull(),
            'from' => $this->date()->notNull(),
            'to' => $this->date()->notNull(),
            "total_amount" => $this->decimal(10, 3)->notNull(), // total amount
            "cost_amount" =>  $this->decimal(10, 3)->notNull(),
            "created_by" => $this->integer(11)->notNull(),
            "updated_by" => $this->integer(11),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-store_domain_subscription_payment',
            'store_domain_subscription_payment',
            'subscription_uuid');

        $this->createIndex('idx-store_domain_subscription_payment-subscription_uuid',
            'store_domain_subscription_payment',
            'subscription_uuid');

        $this->addForeignKey('fk-store_domain_subscription_payment-subscription_uuid',
            'store_domain_subscription_payment',
            'subscription_uuid',
            'store_domain_subscription',
            'subscription_uuid');

        $this->createIndex('idx-store_domain_subscription_payment-created_by',
            'store_domain_subscription_payment',
            'created_by');

        $this->addForeignKey('fk-store_domain_subscription_payment-created_by',
            'store_domain_subscription_payment',
            'created_by',
            'admin',
            'admin_id');

        $this->createIndex('idx-store_domain_subscription_payment-updated_by',
            'store_domain_subscription_payment',
            'updated_by');

        $this->addForeignKey('fk-store_domain_subscription_payment-updated_by',
            'store_domain_subscription_payment',
            'updated_by',
            'admin',
            'admin_id');

        //invoice_item

        $this->addColumn("invoice_item", "store_domain_subscription_payment_uuid",
            $this->char(60)->notNull()->after("order_uuid"));
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
        echo "m250327_155428_store_domain_subscription_payment cannot be reverted.\n";

        return false;
    }
    */
}
