<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%restaurant}}`.
 */
class m200119_140530_create_restaurant_table extends Migration {

    /**
     * {@inheritdoc}
     */
    public function safeUp() {

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%restaurant}}', [
            'restaurant_uuid' => $this->char(60)->unique(),
            'agent_id' => $this->bigInteger()->notNull(),
            'name' => $this->string(255)->notNull(),
            'name_ar' => $this->string(255)->null(),
            'tagline' => ' varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
            'tagline_ar' => ' varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
            'restaurant_status' => $this->smallInteger(1)->defaultValue(1)->notNull(),
            'thumbnail_image' => $this->string()->notNull(),
            'logo' => $this->string(255)->notNull(),
            'support_delivery' => $this->tinyInteger(1)->notNull(),
            'support_pick_up' => $this->tinyInteger(1)->notNull(),
            'phone_number' => $this->string(255),
            'restaurant_created_at' => $this->dateTime(),
            'restaurant_updated_at' => $this->dateTime(),
         ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant', 'restaurant_uuid');

        // creates index for column `agent_id`
        $this->createIndex(
                'idx-restaurant-agent_id',
                'restaurant',
                'agent_id'
        );

        // add foreign key for table `agent`
        $this->addForeignKey(
                'fk-restaurant-agent_id',
                'restaurant',
                'agent_id',
                'agent',
                'agent_id',
                'CASCADE'
        );
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown() {
        $this->dropForeignKey('fk-restaurant-agent_id', 'restaurant');
        $this->dropIndex('idx-restaurant-agent_id', 'restaurant');
        
        $this->dropTable('{{%restaurant}}');
    }

}
