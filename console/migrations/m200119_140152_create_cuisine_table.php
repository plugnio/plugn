<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%cuisine}}`.
 */
class m200119_140152_create_cuisine_table extends Migration
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
        
        $this->createTable('{{%cuisine}}', [
            'cuisine_id' => $this->primaryKey(),
            'cuisine_name' => $this->string(255)->notNull(),
            'cuisine_name_ar' => $this->string(255)
        ],$tableOptions );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%cuisine}}');
    }
}
