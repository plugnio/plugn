<?php

use yii\db\Migration;

/**
 * Class m230418_090645_restaurant_upload
 */
class m230418_090645_restaurant_upload extends Migration
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

        $this->createTable('restaurant_upload', [
            "upload_uuid" => $this->char(60)->notNull(), // used as reference id
            "restaurant_uuid" => $this->char(60)->notNull(),
            "path" => $this->string()->defaultValue("")->comment("path in store built/www"),
            "filename" => $this->string()->notNull(),
            'content' => $this->text(),
            //'status'=> $this->tinyInteger(0)->defaultValue(0),//pending - uploading - uploaded
            'created_by' => $this->bigInteger(20),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_upload', 'upload_uuid');

        $this->createIndex(
            'idx-restaurant_upload-restaurant_uuid',
            'restaurant_upload',
            'restaurant_uuid'
        );

        $this->addForeignKey(
            'fk-restaurant_upload-restaurant_uuid',
            'restaurant_upload',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid'
        );

        $this->createIndex(
            'idx-restaurant_upload-created_by',
            'restaurant_upload',
            'created_by'
        );

        $this->addForeignKey(
            'fk-restaurant_upload-created_by',
            'restaurant_upload',
            'created_by',
            'agent',
            'agent_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230418_090645_restaurant_upload cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230418_090645_restaurant_upload cannot be reverted.\n";

        return false;
    }
    */
}
