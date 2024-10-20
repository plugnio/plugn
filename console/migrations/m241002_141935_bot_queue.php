<?php

use yii\db\Migration;

/**
 * Class m241002_141935_bot_queue
 */
class m241002_141935_bot_queue extends Migration
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

        $this->createTable('{{%restaurant_chat_bot_queue}}', [
            'rcbq_uuid' => $this->char(60),
            "restaurant_uuid" => $this->char(60),
            "status" => $this->tinyInteger(1)->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_chat_bot_queue', 'rcbq_uuid');

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-restaurant_chat_bot_queue-restaurant_uuid', 'restaurant_chat_bot_queue', 'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-restaurant_chat_bot_queue-restaurant_uuid', 'restaurant_chat_bot_queue', 'restaurant_uuid', 'restaurant', 'restaurant_uuid'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241002_141935_bot_queue cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241002_141935_bot_queue cannot be reverted.\n";

        return false;
    }
    */
}
