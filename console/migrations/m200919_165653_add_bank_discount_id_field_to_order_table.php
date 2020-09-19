<?php

use yii\db\Migration;

/**
 * Class m200919_165653_add_bank_discount_id_field_to_order_table
 */
class m200919_165653_add_bank_discount_id_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

              $this->addColumn('order', 'bank_discount_id',  $this->bigInteger());


              // creates index for column `bank_id`
              $this->createIndex(
                      'idx-order-bank_discount_id',
                      'order',
                      'bank_discount_id'
              );

              // add foreign key for table `order`
              $this->addForeignKey(
                      'fk-order-bank_discount_id',
                      'order',
                      'bank_discount_id',
                      'bank_discount',
                      'bank_discount_id',
                      'CASCADE'
              );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropForeignKey('fk-order-bank_discount_id', 'order');
      $this->dropIndex('idx-order-bank_discount_id', 'order');

      $this->dropColumn('order', 'bank_discount_id');

    }

}
