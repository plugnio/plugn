<?php

use yii\db\Migration;

/**
 * Class m230810_042639_page
 */
class m230810_042639_page extends Migration
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

        $this->createTable('{{%restaurant_page}}', [
            'page_uuid' => $this->char(60)->notNull(), // used as reference id
            'restaurant_uuid' => $this->char(60)->notNull(), // used as reference id
            'title' => $this->string(),
            'title_ar' => $this->string(),
            'description' => $this->text(),
            'description_ar' => $this->text(),
            'slug' => $this->string(),
            'sort_number' => $this->integer()->unsigned(),
            'created_by' => $this->bigInteger(20),
            'updated_by' => $this->bigInteger(20),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_page', 'page_uuid');

        $this->addForeignKey(
            'fk-restaurant_page-shipping_method_id', 'restaurant_page',
            'restaurant_uuid', 'restaurant', 'restaurant_uuid', "CASCADE"
        );

        $this->addForeignKey(
            'fk-restaurant_page-created_by', 'restaurant_page', 'created_by',
            'agent', 'agent_id'
        );

        $this->addForeignKey(
            'fk-restaurant_page-updated_by', 'restaurant_page', 'updated_by',
            'agent', 'agent_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-restaurant_page-shipping_method_id', 'restaurant_page',
        );

        $this->dropForeignKey(
            'fk-restaurant_page-created_by', 'restaurant_page',
        );

        $this->dropForeignKey(
            'fk-restaurant_page-updated_by', 'restaurant_page'
        );

        $this->dropTable('{{%restaurant_page}}');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230810_042639_page cannot be reverted.\n";

        return false;
    }
    */
}
