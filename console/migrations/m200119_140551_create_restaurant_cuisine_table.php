<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%restaurant_cuisine}}`.
 */
class m200119_140551_create_restaurant_cuisine_table extends Migration
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

        
        $this->createTable('{{%restaurant_cuisine}}', [
            'restaurant_uuid' => $this->char(36)->notNull(),
            'cuisine_id' => $this->integer()->notNull()
        ],$tableOptions);

        
        $this->addPrimaryKey('PK', 'restaurant_cuisine', ['restaurant_uuid','cuisine_id']);

                
        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-restaurant_cuisine-restaurant_uuid',
            'restaurant_cuisine',
            'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-restaurant_cuisine-restaurant_uuid',
            'restaurant_cuisine',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'CASCADE'
        );
        
        
        // creates index for column `cuisine_id`
        $this->createIndex(
            'idx-restaurant_cuisine-cuisine_id',
            'restaurant_cuisine',
            'cuisine_id'
        );

        // add foreign key for table `cuisine`
        $this->addForeignKey(
            'fk-restaurant_cuisine-cuisine_id',
            'restaurant_cuisine',
            'cuisine_id',
            'cuisine',
            'cuisine_id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-restaurant_cuisine-restaurant_uuid', 'restaurant_cuisine');
        $this->dropIndex('idx-restaurant_cuisine-restaurant_uuid', 'restaurant_cuisine');
        $this->dropForeignKey('fk-restaurant_cuisine-cuisine_id', 'restaurant_cuisine');
        $this->dropIndex('idx-restaurant_cuisine-cuisine_id', 'restaurant_cuisine');
        
        $this->dropTable('{{%restaurant_cuisine}}');
    }
}
