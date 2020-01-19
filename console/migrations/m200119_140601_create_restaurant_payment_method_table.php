<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%restaurant_payment_method}}`.
 */
class m200119_140601_create_restaurant_payment_method_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%restaurant_payment_method}}', [
            'restaurant_uuid' => $this->char(36)->notNull(),
            'payment_method_id' => $this->integer()->notNull(),
        ]);
        
        $this->addPrimaryKey('PK', 'restaurant_payment_method', ['restaurant_uuid','payment_method_id']);
                
        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-restaurant_payment_method-restaurant_uuid',
            'restaurant_payment_method',
            'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-restaurant_payment_method-restaurant_uuid',
            'restaurant_payment_method',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'CASCADE'
        );
        
        
        // creates index for column `payment_method_id`
        $this->createIndex(
            'idx-restaurant_payment_method-payment_method_id',
            'restaurant_payment_method',
            'payment_method_id'
        );

        // add foreign key for table `payment_method`
        $this->addForeignKey(
            'fk-restaurant_payment_method-payment_method_id',
            'restaurant_payment_method',
            'payment_method_id',
            'payment_method',
            'payment_method_id',
            'CASCADE'
        );
        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-restaurant_payment_method-restaurant_uuid', 'restaurant_payment_method');
        $this->dropForeignKey('fk-restaurant_payment_method-payment_method_id', 'restaurant_payment_method');
        $this->dropIndex('idx-restaurant_payment_method-restaurant_uuid', 'restaurant_payment_method');
        $this->dropIndex('idx-restaurant_payment_method-payment_method_id', 'restaurant_payment_method');
        $this->dropTable('{{%restaurant_payment_method}}');
    }
}
