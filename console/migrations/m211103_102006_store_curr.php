<?php

use yii\db\Migration;

/**
 * Class m211103_102006_store_curr
 */
class m211103_102006_store_curr extends Migration
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

        $this->createTable('{{%restaurant_currency}}', [
            'restaurant_currency_uuid' => $this->char(60)->notNull(),
            'restaurant_uuid' => $this->char(60)->notNull(),
            'currency_id' => $this->integer (11),
            'created_at'=> $this->dateTime(),
            'updated_at'=> $this->dateTime()
        ],$tableOptions);

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-restaurant_currency-restaurant_uuid',
            'restaurant_currency',
            'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-restaurant_currency-restaurant_uuid',
            'restaurant_currency',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'CASCADE'
        );

        // creates index for column `currency_id`
        $this->createIndex(
            'idx-restaurant_currency-currency_id',
            'restaurant_currency',
            'currency_id'
        );

        // add foreign key for table `currency`
        $this->addForeignKey(
            'fk-restaurant_currency-currency_id',
            'restaurant_currency',
            'currency_id',
            'currency',
            'currency_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211103_102006_store_curr cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211103_102006_store_curr cannot be reverted.\n";

        return false;
    }
    */
}
