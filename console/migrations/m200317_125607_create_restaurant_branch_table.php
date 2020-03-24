<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%restaurant_branch}}`.
 */
class m200317_125607_create_restaurant_branch_table extends Migration
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

        
        $this->createTable('{{%restaurant_branch}}', [
            'restaurant_branch_id' => $this->primaryKey(),
            'restaurant_uuid' => $this->char(60),
            'branch_name_en' => $this->string(),
            'branch_name_ar' => $this->string(),
            'prep_time' => $this->integer()->unsigned(),

        ],$tableOptions);
        
        
        // creates index for column `restaurant_uuid`
        $this->createIndex(
                'idx-restaurant_branch-restaurant_uuid',
                'restaurant_branch',
                'restaurant_uuid'
        );

        // add foreign key for table `restaurant_branch`
        $this->addForeignKey(
                'fk-restaurant_branch-restaurant_uuid', 
                'restaurant_branch',
                'restaurant_uuid', 
                'restaurant',
                'restaurant_uuid', 
                'CASCADE'
        );
        
        $this->addColumn('order', 'restaurant_branch_id', $this->integer());
        
        // creates index for column `restaurant_branch_id`
        $this->createIndex(
                'idx-order-restaurant_branch_id',
                'order',
                'restaurant_branch_id'
        );

        // add foreign key for table `order`
        $this->addForeignKey(
                'fk-order-restaurant_branch_id', 
                'order',
                'restaurant_branch_id', 
                'restaurant_branch',
                'restaurant_branch_id', 
                'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%restaurant_branch}}');
    }
}
