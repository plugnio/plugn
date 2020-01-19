<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%area}}`.
 */
class m200119_140111_create_area_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%area}}', [
            'area_id' => $this->primaryKey(),
            'city_id' => $this->integer()->notNull(),
            'area_name' => $this->string(255)->notNull(),
            'area_name_ar' => $this->string(255)->notNull(),
            'latitude' => $this->decimal(9, 6),
            'longitude' => $this->decimal(9, 6),
        ],$tableOptions);
        
        // creates index for column `city_id`
        $this->createIndex(
                'idx-area-city_id',
                'area', 
                'city_id'
        );

        // add foreign key for table `city`
        $this->addForeignKey(
                'fk-area-city_id',
                'area', 
                'city_id',
                'city',
                'city_id',
                'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-area-city_id', 'area');
        $this->dropIndex('idx-area-city_id', 'area');
        $this->dropTable('{{%area}}');
    }
}
