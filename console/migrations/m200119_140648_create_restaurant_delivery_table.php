<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%restaurant_delivery}}`.
 */
class m200119_140648_create_restaurant_delivery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%restaurant_delivery}}', [
            'restaurant_uuid' => $this->char(36)->notNull(),
            'area_id' => $this->integer()->notNull(),
        ]);
        
        $this->addPrimaryKey('PK', 'restaurant_delivery', ['restaurant_uuid','area_id']);

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-restaurant_delivery-restaurant_uuid',
            'restaurant_delivery',
            'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-restaurant_delivery-restaurant_uuid',
            'restaurant_delivery',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'CASCADE'
        );
        
        
        // creates index for column `area_id`
        $this->createIndex(
            'idx-restaurant_delivery-area_id',
            'restaurant_delivery',
            'area_id'
        );

        // add foreign key for table `area`
        $this->addForeignKey(
            'fk-restaurant_delivery-area_id',
            'restaurant_delivery',
            'area_id',
            'area',
            'area_id',
            'CASCADE'
        );
        
        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-restaurant_delivery-restaurant_uuid', 'restaurant_delivery');
        $this->dropIndex('idx-restaurant_delivery-restaurant_uuid', 'restaurant_delivery');
        $this->dropForeignKey('fk-restaurant_delivery-area_id', 'restaurant_delivery');
        $this->dropIndex('idx-restaurant_delivery-area_id', 'restaurant_delivery');
        
        $this->dropTable('{{%restaurant_delivery}}');
    }
}
