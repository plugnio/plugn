<?php

use yii\db\Migration;

/**
 * Class m210803_132951_add_partner_fee_field_to_subscription_payment_table
 */
class m210803_132951_add_partner_fee_field_to_subscription_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

      //drop payment_uuid field from partner_payout table
      $this->dropForeignKey('fk-partner_payout-payment_uuid', 'partner_payout');
      $this->dropIndex('idx-partner_payout-payment_uuid', 'partner_payout');


      $this->dropColumn('partner_payout', 'payment_uuid');

      $this->addColumn('subscription_payment', 'partner_fee',  $this->decimal(10,3)->unsigned()->defaultValue(0));
      $this->addColumn('subscription_payment', 'payout_status', $this->smallInteger()->defaultValue(0));

      $this->addColumn('subscription_payment', 'partner_payout_uuid', $this->char(60));

      // creates index for column `partner_payout_uuid`
      $this->createIndex(
              'idx-subscription_payment-partner_payout_uuid', 'subscription_payment', 'partner_payout_uuid'
      );

      // add foreign key for table `subscription_payment`
      $this->addForeignKey(
              'fk-subscription_payment-partner_payout_uuid', 'subscription_payment', 'partner_payout_uuid', 'partner_payout', 'partner_payout_uuid', 'SET NULL', 'SET NULL'
      );

      $this->addColumn('payment', 'partner_payout_uuid', $this->char(60));

      // creates index for column `partner_payout_uuid`
      $this->createIndex(
              'idx-payment-partner_payout_uuid', 'payment', 'partner_payout_uuid'
      );

      // add foreign key for table `partner_payout`
      $this->addForeignKey(
              'fk-payment-partner_payout_uuid', 'payment', 'partner_payout_uuid', 'partner_payout', 'partner_payout_uuid', 'SET NULL', 'SET NULL'
      );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

      $this->addColumn('partner_payout', 'payment_uuid', $this->char(60));

      // creates index for column `payment_uuid`
      $this->createIndex(
              'idx-partner_payout-payment_uuid', 'partner_payout', 'payment_uuid'
      );

      // add foreign key for table `partner_payout`
      $this->addForeignKey(
              'fk-partner_payout-payment_uuid', 'partner_payout', 'payment_uuid', 'payment', 'payment_uuid', 'CASCADE'
      );


      $this->dropColumn('subscription_payment', 'partner_fee');
      $this->dropColumn('subscription_payment', 'payout_status');


      //Drop subscription_payment_uuid field
      $this->dropForeignKey('fk-subscription_payment-partner_payout_uuid', 'subscription_payment');
      $this->dropIndex('idx-subscription_payment-partner_payout_uuid', 'subscription_payment');

      //Drop payment_uuid field
      $this->dropForeignKey('fk-payment-partner_payout_uuid', 'payment');
      $this->dropIndex('idx-payment-partner_payout_uuid', 'payment');

      $this->dropColumn('partner_payout', 'subscription_payment_uuid');
    }

}
