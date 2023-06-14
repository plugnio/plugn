<?php

use yii\db\Migration;

/**
 * Class m230614_083150_video
 */
class m230614_083150_video extends Migration
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

        $this->createTable('item_video', [
            "item_video_id" => $this->primaryKey(20), // used as reference id
            "item_uuid" => $this->string(300)->notNull(),
            'youtube_video_id' => $this->string(20)->notNull(),
            "product_file_name" => $this->string(),
            "created_at" => $this->dateTime(),
            "updated_at" => $this->dateTime(),
        ], $tableOptions);

        $this->createIndex(
            'idx-item_video-item_uuid',
            'item_video',
            'item_uuid'
        );

        $this->addForeignKey(
            'fk-item_video-item_uuid',
            'item_video',
            'item_uuid',
            'item',
            'item_uuid'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230614_083150_video cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230614_083150_video cannot be reverted.\n";

        return false;
    }
    */
}
