<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m200119_140720_create_category_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {
        
        
          $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%category}}', [
            'category_id' => $this->primaryKey(),
            'restaurant_uuid' => $this->char(60),
            'category_name' => $this->string(),
            'category_name_ar' => $this->string(),
            'sort_number' => $this->integer()->unsigned(),
        ],$tableOptions);
        
        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-category-restaurant_uuid',
            'category',
            'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-category-restaurant_uuid',
            'category',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'CASCADE'
        );
        
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropTable('{{%category}}');
    }

}
