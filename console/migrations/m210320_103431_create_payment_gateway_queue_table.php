<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_gateway_queue}}`.
 */
class m210320_103431_create_payment_gateway_queue_table extends Migration
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

      $this->createTable('{{%payment_gateway_queue}}', [
          'payment_gateway_queue_id' => $this->primaryKey(),
          'restaurant_uuid' => $this->char(60)->notNull(),
          'payment_gateway' => $this->string()->notNull(),
          'queue_status'=> $this->smallInteger()->defaultValue(1),  //1 pending, 2 creating store , 3 complete
          'queue_created_at'=> $this->dateTime(),
          'queue_updated_at'=> $this->dateTime(),
          'queue_start_at'=> $this->dateTime(),
          'queue_end_at'=> $this->dateTime(),

      ],$tableOptions);

      // creates index for column `restaurant_uuid`
      $this->createIndex(
              'idx-payment_gateway_queue-restaurant_uuid',
              'payment_gateway_queue',
              'restaurant_uuid'
      );

      // add foreign key for table `payment_gateway_queue`
      $this->addForeignKey(
              'fk-payment_gateway_queue-restaurant_uuid',
              'payment_gateway_queue',
              'restaurant_uuid',
              'restaurant',
              'restaurant_uuid',
              'CASCADE'
      );



      $this->addColumn('restaurant', 'payment_gateway_queue_id', $this->integer());

      // creates index for column `payment_gateway_queue_id`
      $this->createIndex(
              'idx-restaurant-payment_gateway_queue_id',
              'restaurant',
              'payment_gateway_queue_id'
      );

      // add foreign key for table `restaurant`
      $this->addForeignKey(
              'fk-restaurant-payment_gateway_queue_id',
              'restaurant',
              'payment_gateway_queue_id',
              'payment_gateway_queue',
              'payment_gateway_queue_id',
              'SET NULL'
      );

  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropForeignKey('fk-restaurant-payment_gateway_queue_id', 'restaurant');
    $this->dropIndex('idx-restaurant-payment_gateway_queue_id', 'restaurant');
    $this->dropColumn('restaurant', 'payment_gateway_queue_id');


    $this->dropForeignKey('fk-payment_gateway_queue-restaurant_uuid', 'payment_gateway_queue');
    $this->dropIndex('idx-payment_gateway_queue-restaurant_uuid', 'payment_gateway_queue');

    $this->dropTable('{{%payment_gateway_queue}}');
  }
}
