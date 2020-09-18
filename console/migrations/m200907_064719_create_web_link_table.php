<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%web_link}}`.
 */
class m200907_064719_create_web_link_table extends Migration
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

        $this->createTable('{{%web_link}}', [
            'web_link_id' => $this->bigPrimaryKey(),
            'restaurant_uuid' => $this->char(60)->notNull(),
            'web_link_type' => $this->smallInteger()->defaultValue(0)->notNull(),
            'url' => $this->string()->notNull(),
            'web_link_title' => $this->string()->notNull(),
            'web_link_title_ar' => $this->string()->notNull(),
        ],$tableOptions);


        // creates index for column `restaurant_uuid`
        $this->createIndex(
                'idx-web_link-restaurant_uuid',
                'web_link',
                'restaurant_uuid'
        );

        // add foreign key for table `web_link`
        $this->addForeignKey(
                'fk-web_link-restaurant_uuid',
                'web_link',
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
        $this->dropForeignKey('fk-web_link-restaurant_uuid', 'web_link');
        $this->dropIndex('idx-web_link-restaurant_uuid', 'web_link');

        $this->dropTable('{{%web_link}}');
    }
}
