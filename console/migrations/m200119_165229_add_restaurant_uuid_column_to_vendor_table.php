<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%vendor}}`.
 */
class m200119_165229_add_restaurant_uuid_column_to_vendor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        
        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-vendor-restaurant_uuid',
            'vendor',
            'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-vendor-restaurant_uuid',
            'vendor',
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
        $this->dropForeignKey('fk-vendor-restaurant_uuid', 'vendor');
        $this->dropForeignKey('idx-vendor-restaurant_uuid', 'vendor');
    }
}
