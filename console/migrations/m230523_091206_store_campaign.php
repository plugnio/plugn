<?php

use yii\db\Migration;

/**
 * Class m230523_091206_store_campaign
 */
class m230523_091206_store_campaign extends Migration
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

        $this->createTable('restaurant_by_campaign', [
            "rbc_uuid" => $this->char(60)->notNull(), // used as reference id
            "restaurant_uuid" => $this->char(60)->notNull(),
            "utm_uuid" => $this->char(60)->notNull(),
            'created_by' => $this->bigInteger(20),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_by_campaign', 'rbc_uuid');

        $this->createIndex(
            'idx-restaurant_by_campaign-utm_uuid',
            'restaurant_by_campaign',
            'utm_uuid'
        );

        $this->addForeignKey(
            'fk-restaurant_by_campaign-utm_uuid',
            'restaurant_by_campaign',
            'utm_uuid',
            'campaign',
            'utm_uuid'
        );

        $this->createIndex(
            'idx-restaurant_by_campaign-restaurant_uuid',
            'restaurant_by_campaign',
            'restaurant_uuid'
        );

        $this->addForeignKey(
            'fk-restaurant_by_campaign-restaurant_uuid',
            'restaurant_by_campaign',
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
        echo "m230523_091206_store_campaign cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230523_091206_store_campaign cannot be reverted.\n";

        return false;
    }
    */
}
