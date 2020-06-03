<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%item_image}}`.
 */
class m200602_203200_create_item_image_table extends Migration
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

        $this->createTable('{{%item_image}}', [
            'item_image_id' => $this->bigPrimaryKey(),
            'item_uuid' => $this->string(300),
            'product_file_name' => $this->string(),
        ],$tableOptions);

        // add foreign key for `item_uuid` in table `payment`
        $this->addForeignKey(
                'fk-item_image-item_uuid', 'item_image', 'item_uuid', 'item', 'item_uuid', 'CASCADE'
        );


        $this->createIndex(
                'idx-item_image-item_uuid', 'item_image', 'item_uuid'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-item_image-item_uuid','item_image');
        $this->dropIndex('idx-item_image-item_uuid','item_image');

        $this->dropTable('{{%item_image}}');
    }
}
