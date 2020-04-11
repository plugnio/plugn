<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%restaurant_theme}}`.
 */
class m200411_113002_create_restaurant_theme_table extends Migration
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

        $this->createTable('{{%restaurant_theme}}', [
            'restaurant_uuid' => $this->char(60),
            'primary' => $this->char(60)->defaultValue('#3880ff'),
            'secondary' => $this->char(60)->defaultValue('#3dc2ff'),
            'tertiary' => $this->char(60)->defaultValue('#5260ff'),
            'light' => $this->char(60)->defaultValue('#f4f5f8'),
            'medium' => $this->char(60)->defaultValue('#92949c'),
            'dark' => $this->char(60)->defaultValue('#222428'),
        ],$tableOptions);
        
        $this->addPrimaryKey('PK', 'restaurant_theme', ['restaurant_uuid']);

        // creates index for column `restaurant_uuid`
        $this->createIndex(
                'idx-restaurant_theme-restaurant_uuid',
                'restaurant_theme',
                'restaurant_uuid'
        );

        // add foreign key for table `restaurant_theme`
        $this->addForeignKey(
                'fk-restaurant_theme-restaurant_uuid', 
                'restaurant_theme',
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
        $this->dropIndex('idx-restaurant_theme-restaurant_uuid', 'restaurant_theme');
        $this->dropForeignKey('fk-restaurant_theme-restaurant_uuid', 'restaurant_theme');
        $this->dropPrimaryKey('PK', 'restaurant_theme');
        $this->dropTable('{{%restaurant_theme}}');
    }
}
