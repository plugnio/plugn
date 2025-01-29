<?php

use yii\db\Migration;

/**
 * Class m250129_050921_tap_error
 */
class m250129_050921_tap_error extends Migration
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

        $this->createTable('{{%tap_error}}', [
            'tap_error_uuid' => $this->char(60),
            "restaurant_uuid" => $this->char(60)->notNull(),
            "title" => $this->string(),
            "message" => $this->string(),
            "text" => $this->text(),
            "issue_logged" => $this->integer(5)->defaultValue(1)
                ->comment("No of time issue logged"),
            "status" => $this->tinyInteger(2)->defaultValue(10),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

         $this->addPrimaryKey('PK', 'tap_error', 'tap_error_uuid');

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-tap_error-restaurant_uuid', 'tap_error', 'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-tap_error-restaurant_uuid',
            'tap_error',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            "CASCADE",
            "CASCADE"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tap_error}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250129_050921_tap_error cannot be reverted.\n";

        return false;
    }
    */
}
