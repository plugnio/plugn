<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category_item}}`.
 */
class m200119_140733_create_category_item_table extends Migration
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

        
        $this->createTable('{{%category_item}}', [
            'category_id' => $this->integer()->notNull(),
            'item_uuid' => $this->string(300)->notNull(),
        ],$tableOptions);
        
       $this->addPrimaryKey('PK', 'category_item', ['category_id','item_uuid']);

        // creates index for column `item_uuid`
        $this->createIndex(
            'idx-category_item-item_uuid',
            'category_item',
            'item_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-category_item-item_uuid',
            'category_item',
            'item_uuid',
            'item',
            'item_uuid',
            'CASCADE'
        );
        
        
        // creates index for column `category_id`
        $this->createIndex(
            'idx-category_item-category_id',
            'category_item',
            'category_id'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-category_item-category_id',
            'category_item',
            'category_id',
            'category',
            'category_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-category_item-item_uuid', 'category_item');
        $this->dropIndex('idx-category_item-item_uuid', 'category_item');
        $this->dropForeignKey('fk-category_item-category_id', 'category_item');
        $this->dropIndex('idx-category_item-category_id', 'category_item');
        
        $this->dropTable('{{%category_item}}');
    }
}
