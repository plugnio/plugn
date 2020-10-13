<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%queue}}`.
 */
class m201012_184039_create_queue_table extends Migration
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

        $this->createTable('{{%queue}}', [
            'queue_id' => $this->primaryKey(),
            'restaurant_uuid' => $this->char(60)->notNull(),
            'queue_status'=> $this->smallInteger()->defaultValue(1),  //1 pending, 2 creating store , 3 complete
            'queue_created_at'=> $this->dateTime(),
            'queue_updated_at'=> $this->dateTime(),
            'queue_start_at'=> $this->dateTime(),
            'queue_end_at'=> $this->dateTime(),

        ],$tableOptions);

        // creates index for column `restaurant_uuid`
        $this->createIndex(
                'idx-queue-restaurant_uuid',
                'queue',
                'restaurant_uuid'
        );

        // add foreign key for table `queue`
        $this->addForeignKey(
                'fk-queue-restaurant_uuid',
                'queue',
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
        $this->dropForeignKey('fk-queue-restaurant_uuid', 'queue');
        $this->dropIndex('idx-queue-restaurant_uuid', 'queue');

        $this->dropTable('{{%queue}}');
    }
}
