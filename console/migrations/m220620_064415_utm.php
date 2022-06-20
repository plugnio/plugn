<?php

use yii\db\Migration;

/**
 * Class m220620_064415_utm
 */
class m220620_064415_utm extends Migration
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

        $this->createTable('{{%campaign}}', [
            'utm_uuid'=> $this->char(60),
            'restaurant_uuid' => $this->char(60),
            'utm_source' => $this->string(100)->comment('e.g. newsletter, twitter, google, etc.'),
            'utm_medium' => $this->string(100)->comment('e.g. email, social, cpc, etc.'),
            'utm_campaign' => $this->string(100)->comment('e.g. promotion, sale, etc.'),
            'utm_content' => $this->string(100)->comment('Any call-to-action or headline, e.g. buy-now.'),
            'utm_term' => $this->string(100)->comment('Keywords for your paid search campaigns'),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'campaign', 'utm_uuid');

        $this->createIndex(
            'idx-campaign-restaurant_uuid', 'restaurant', 'restaurant_uuid'
        );

        $this->addForeignKey(
            'fk-campaign-restaurant_uuid', 'campaign', 'restaurant_uuid', 'restaurant', 'restaurant_uuid'
        );

        $this->addColumn('order', 'utm_uuid', $this->string(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220620_064415_utm cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220620_064415_utm cannot be reverted.\n";

        return false;
    }
    */
}
