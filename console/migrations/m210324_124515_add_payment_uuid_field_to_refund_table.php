<?php

use yii\db\Migration;

/**
 * Class m210324_124515_add_payment_uuid_field_to_refund_table
 */
class m210324_124515_add_payment_uuid_field_to_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

      // Add field to refund so each order has a child payment
      $this->addColumn('refund', 'payment_uuid', $this->char(36)->after('refund_id'));
      // creates index for column `payment_uuid`in table `refund`
      $this->createIndex(
          'idx-refund-payment_uuid',
          'refund',
          'payment_uuid'
      );

      // add foreign key for `payment_uuid` in table `refund`
      $this->addForeignKey(
          'fk-refund-payment_uuid',
          'refund',
          'payment_uuid',
          'payment',
          'payment_uuid',
          'SET NULL',
          'CASCADE'
      );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropForeignKey('fk-refund-payment_uuid', 'refund');
      $this->dropIndex('idx-refund-payment_uuid', 'refund');

      $this->dropColumn('refund', 'payment_uuid');

    }

}
