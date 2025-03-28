<?php

use yii\db\Migration;

/**
 * Class m250326_152353_store_domain_subscription
 */
class m250326_152353_store_domain_subscription extends Migration
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

        //$this->dropTable('store_domain_subscription');

        $this->createTable('store_domain_subscription', [
            'subscription_uuid' => $this->char(60)->notNull(),
            'restaurant_uuid' => $this->char(60),
            'domain_registrar' => $this->string(100)->notNull(),
            'domain' => $this->string(100)->notNull(),
            'from' => $this->date()->notNull(),
            'to' => $this->date()->notNull(),
            "created_by" => $this->integer(11)->notNull(),
            "updated_by" => $this->integer(11),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('pk-store_domain_subscription',
            'store_domain_subscription',
            'subscription_uuid');

        $this->createIndex('idx-store_domain_subscription-restaurant_uuid',
            'store_domain_subscription',
            'restaurant_uuid');

        $this->addForeignKey('fk-store_domain_subscription-restaurant_uuid',
            'store_domain_subscription',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid');

        $this->createIndex('idx-store_domain_subscription-created_by',
            'store_domain_subscription',
            'created_by');

        $this->addForeignKey('fk-store_domain_subscription-created_by',
            'store_domain_subscription',
            'created_by',
            'admin',
            'admin_id');

        $this->createIndex('idx-store_domain_subscription-updated_by',
            'store_domain_subscription',
            'updated_by');

        $this->addForeignKey('fk-store_domain_subscription-updated_by',
            'store_domain_subscription',
            'updated_by',
            'admin',
            'admin_id');

        //invoice_item

        $this->addColumn("invoice_item", "domain_subscription_uuid",
            $this->char(60)->notNull()->after("order_uuid"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250326_152353_store_domain_subscription cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250326_152353_store_domain_subscription cannot be reverted.\n";

        return false;
    }
    */
}
