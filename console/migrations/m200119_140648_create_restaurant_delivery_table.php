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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        
        $this->createTable('{{%restaurant_delivery}}', [
            'restaurant_uuid' => $this->char(60)->notNull(),
            'area_id' => $this->integer()->notNull(),
            'delivery_time' => $this->integer()->unsigned()->defaultValue(60),
            'delivery_fee' => $this->float()->unsigned()->defaultValue(0),
            'min_charge' => $this->float()->unsigned()->defaultValue(0),
        ],$tableOptions);
        
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
