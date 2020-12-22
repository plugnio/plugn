<?php

use yii\db\Migration;

/**
 * Class m201222_175532_add_pickup_location_id_field_to_order_table
 */
class m201222_175532_add_pickup_location_id_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

      $this->addColumn('order', 'pickup_location_id', $this->bigInteger());

      // creates index for column `pickup_location_id`
      $this->createIndex(
              'idx-order-pickup_location_id',
              'order',
              'pickup_location_id'
      );

      // add foreign key for table `order`
      $this->addForeignKey(
              'fk-order-pickup_location_id',
              'order',
              'pickup_location_id',
              'business_location',
              'business_location_id',
              'CASCADE'
      );


      $this->addColumn('item', 'prep_time', $this->integer());
      $this->addColumn('item', 'prep_time_unit', $this->char(3)->defaultValue('min')->notNull());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropForeignKey('fk-order-pickup_location_id', 'order');
      $this->dropIndex('idx-order-pickup_location_id', 'order');

      $this->dropColumn('order', 'pickup_location_id');
      $this->dropColumn('item', 'prep_time');
      $this->dropColumn('item', 'prep_time_unit');
    }

}
