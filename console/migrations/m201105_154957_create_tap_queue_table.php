<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tap_queue}}`.
 */
class m201105_154957_create_tap_queue_table extends Migration
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

          $this->createTable('{{%tap_queue}}', [
              'tap_queue_id' => $this->primaryKey(),
              'restaurant_uuid' => $this->char(60)->notNull(),
              'queue_status'=> $this->smallInteger()->defaultValue(1),  //1 pending, 2 creating store , 3 complete
              'queue_created_at'=> $this->dateTime(),
              'queue_updated_at'=> $this->dateTime(),
              'queue_start_at'=> $this->dateTime(),
              'queue_end_at'=> $this->dateTime(),

          ],$tableOptions);

          // creates index for column `restaurant_uuid`
          $this->createIndex(
                  'idx-tap_queue-restaurant_uuid',
                  'tap_queue',
                  'restaurant_uuid'
          );

          // add foreign key for table `tap_queue`
          $this->addForeignKey(
                  'fk-tap_queue-restaurant_uuid',
                  'tap_queue',
                  'restaurant_uuid',
                  'restaurant',
                  'restaurant_uuid',
                  'CASCADE'
          );



          $this->addColumn('restaurant', 'tap_queue_id', $this->integer());

          // creates index for column `tap_queue_id`
          $this->createIndex(
                  'idx-restaurant-tap_queue_id',
                  'restaurant',
                  'tap_queue_id'
          );

          // add foreign key for table `restaurant`
          $this->addForeignKey(
                  'fk-restaurant-tap_queue_id',
                  'restaurant',
                  'tap_queue_id',
                  'tap_queue',
                  'tap_queue_id',
                  'SET NULL'
          );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {


      $this->dropForeignKey('fk-restaurant-tap_queue_id', 'restaurant');
      $this->dropIndex('idx-restaurant-tap_queue_id', 'restaurant');
      $this->dropColumn('restaurant', 'tap_queue_id');


      $this->dropForeignKey('fk-tap_queue-restaurant_uuid', 'tap_queue');
      $this->dropIndex('idx-tap_queue-restaurant_uuid', 'tap_queue');

      $this->dropTable('{{%tap_queue}}');
    }
}
