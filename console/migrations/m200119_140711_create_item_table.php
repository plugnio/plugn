<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%item}}`.
 */
class m200119_140711_create_item_table extends Migration
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

        
        $this->createTable('{{%item}}', [
            'item_uuid' => $this->string(300)->unique(),
            'restaurant_uuid' => $this->char(60)->notNull(),
            'item_name' => $this->string(255),
            'item_name_ar' => $this->string(255),
            'item_description' => $this->string(1000),
            'item_description_ar' => $this->string(1000),
            'sort_number' => $this->integer(),
            'stock_qty' => $this->integer(),
            'item_image' => $this->string(255),
            'item_price' => $this->float(),
            'item_created_at' => $this->dateTime(),
            'item_updated_at' => $this->dateTime(),
        ], $tableOptions);
        
        $this->addPrimaryKey('PK', 'item', 'item_uuid');

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-item-restaurant_uuid',
            'item',
            'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-item-restaurant_uuid',
            'item',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-item-restaurant_uuid', 'item');
        $this->dropIndex('idx-item-restaurant_uuid', 'item');
        $this->dropTable('{{%item}}');
    }
}
