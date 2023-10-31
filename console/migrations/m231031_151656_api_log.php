<?php

use yii\db\Migration;

/**
 * Class m231031_151656_api_log
 */
class m231031_151656_api_log extends Migration
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

        $this->createTable('{{%api_log}}', [
            'log_uuid' => $this->char(60), // used as reference id
            'restaurant_uuid' => $this->char(60)->null(), // used as reference id
            'method' => $this->string(10),
            'endpoint' => $this->text(),
            'request_headers' => $this->text(),
            'request_body' => $this->text(),
            'response_headers' => $this->text(),
            'response_body' => $this->text(),
            'created_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'api_log', 'log_uuid');

        $this->createIndex('ind-api_log-restaurant_uuid', 'api_log', 'restaurant_uuid');

        $this->addForeignKey(
            'fk-api_log-restaurant_uuid', 'api_log',
            'restaurant_uuid', 'restaurant', 'restaurant_uuid', "CASCADE"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('api_log');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231031_151656_api_log cannot be reverted.\n";

        return false;
    }
    */
}
