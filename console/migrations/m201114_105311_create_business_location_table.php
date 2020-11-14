<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%business_location}}`.
 */
class m201114_105311_create_business_location_table extends Migration
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


        $this->createTable('{{%business_location}}', [
            'business_location_id' => $this->bigPrimaryKey(),
            'restaurant_uuid' => $this->char(60)->notNull(),
            'business_location_name' => $this->string()->notNull(),
            'business_location_name_ar' => $this->string()->notNull(),
            'support_delivery' => $this->tinyInteger(1)->notNull(),
            'support_pick_up' => $this->tinyInteger(1)->notNull(),
        ],$tableOptions);



        // creates index for column `restaurant_uuid`
        $this->createIndex(
                'idx-business_location-restaurant_uuid',
                'business_location',
                'restaurant_uuid'
        );

        // add foreign key for table `business_location`
        $this->addForeignKey(
                'fk-business_location-restaurant_uuid',
                'business_location',
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
        $this->dropForeignKey('fk-business_location-restaurant_uuid', 'business_location');
        $this->dropIndex('idx-business_location-restaurant_uuid', 'business_location');

        $this->dropTable('{{%business_location}}');
    }
}
