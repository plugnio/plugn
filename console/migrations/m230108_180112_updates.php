<?php

use yii\db\Migration;

/**
 * Class m230108_180112_updates
 */
class m230108_180112_updates extends Migration
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

        $this->createTable('{{%plugn_updates}}', [
            'update_uuid' => $this->string(60),
            'title'=> $this->string(100)->notNull(),
            'content' => $this->text()->notNull(),
            'title_ar'=> $this->string(100)->notNull(),
            'content_ar' => $this->text()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->createTable('{{%store_updates}}', [
            'store_update_uuid' => $this->string(60),
            'restaurant_uuid' => $this->char(60),
            'title'=> $this->string(100)->notNull(),
            'content' => $this->text()->notNull(),
            'title_ar'=> $this->string(100)->notNull(),
            'content_ar' => $this->text()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime()
        ], $tableOptions);

        $this->createIndex(
            'idx-store_updates-restaurant_uuid', 'store_updates', 'restaurant_uuid'
        );

        $this->addForeignKey(
            'fk-store_updates-restaurant_uuid', 'store_updates', 'restaurant_uuid',
            'restaurant', 'restaurant_uuid'
        );

        $this->addPrimaryKey('PK', 'store_updates', 'store_update_uuid');

        $this->addPrimaryKey('PK', 'plugn_updates', 'update_uuid');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('store_updates');

        $this->dropTable('plugn_updates');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230108_180112_updates cannot be reverted.\n";

        return false;
    }
    */
}
