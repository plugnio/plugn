<?php

use yii\db\Migration;

/**
 * Class m220728_061411_config
 */
class m220728_061411_config extends Migration
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

        $this->createTable('{{%setting}}', [
            'setting_uuid'=> $this->char(60),
            'restaurant_uuid' => $this->char(60)->null(),
            'code' => $this->string(128)->notNull()->comment('module identifier'),
            'key' => $this->string(128)->notNull(),
            'value' => $this->text(),
            'serialized' => $this->tinyInteger(1)->defaultValue(0),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'setting', 'setting_uuid');

        $this->createIndex(
            'idx-setting-restaurant_uuid', 'restaurant', 'restaurant_uuid'
        );

        $this->addForeignKey(
            'fk-setting-restaurant_uuid', 'setting', 'restaurant_uuid', 'restaurant', 'restaurant_uuid'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220728_061411_config cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220728_061411_config cannot be reverted.\n";

        return false;
    }
    */
}
