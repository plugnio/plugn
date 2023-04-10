<?php

use yii\db\Migration;

/**
 * Class m230410_063218_store_domain
 */
class m230410_063218_store_domain extends Migration
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
        /*
pre-owned
assigned

purchase request
pending
purchased
assigned
*/
        $this->createTable('restaurant_domain_request', [
            "request_uuid" => $this->char(60)->notNull(), // used as reference id
            'domain' => $this->string(),
            'status'=> $this->tinyInteger(0)->defaultValue(0),
            'created_by' => $this->bigInteger(20),

            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_domain_request', 'request_uuid');

        $this->createIndex(
            'idx-restaurant_domain_request-created_by',
            'restaurant_domain_request',
            'created_by'
        );

        $this->addForeignKey(
            'fk-restaurant_domain_request-created_by',
            'restaurant_domain_request',
            'created_by',
            'agent',
            'agent_id'
        );

        $this->addColumn('restaurant_domain_request', 'restaurant_uuid', $this->char(60)->after('request_uuid'));
        $this->addColumn('restaurant_domain_request', 'expire_at', $this->date()->after('created_by'));

        $this->createIndex(
            'idx-restaurant_domain_request-restaurant_uuid',
            'restaurant_domain_request',
            'restaurant_uuid'
        );

        $this->addForeignKey(
            'fk-restaurant_domain_request-restaurant_uuid',
            'restaurant_domain_request',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid'
        );

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
        echo "m230410_063218_store_domain cannot be reverted.\n";

        return false;
    }
    */
}
