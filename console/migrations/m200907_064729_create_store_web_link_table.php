<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%store_web_link}}`.
 */
class m200907_064729_create_store_web_link_table extends Migration
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
         
        $this->createTable('{{%store_web_link}}', [
            'web_link_id' => $this->bigInteger(),
            'restaurant_uuid' => $this->char(60)->notNull(),
        ],$tableOptions);
        
        // creates index for column `web_link_id`
        $this->createIndex(
                'idx-store_web_link-web_link_id',
                'store_web_link',
                'web_link_id'
        );

        // add foreign key for table `web_link`
        $this->addForeignKey(
                'fk-store_web_link-web_link_id',
                'store_web_link',
                'web_link_id',
                'web_link',
                'web_link_id',
                'CASCADE'
        );
        
        
        
        // creates index for column `restaurant_uuid`
        $this->createIndex(
                'idx-store_web_link-restaurant_uuid',
                'store_web_link',
                'restaurant_uuid'
        );

        // add foreign key for table `web_link`
        $this->addForeignKey(
                'fk-store_web_link-restaurant_uuid',
                'store_web_link',
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
        $this->dropForeignKey('fk-store_web_link-web_link_id', 'store_web_link');
        $this->dropIndex('idx-store_web_link-web_link_id', 'store_web_link');
        
        $this->dropForeignKey('fk-store_web_link-restaurant_uuid', 'store_web_link');
        $this->dropIndex('idx-store_web_link-restaurant_uuid', 'store_web_link');
                
                
        $this->dropTable('{{%store_web_link}}');
    }
}
