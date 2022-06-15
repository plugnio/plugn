<?php

use yii\db\Migration;

/**
 * Class m210919_091413_add_phone_number_country_code_field_to_partner_table
 */
class m210919_091413_add_phone_number_country_code_field_to_partner_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('partner', 'partner_phone_number', $this->string());
      $this->addColumn('partner', 'partner_phone_number_country_code', $this->integer(3)->defaultValue(965));


      $this->dropForeignKey('fk-subscription_payment-partner_payout_uuid', 'subscription_payment');
      $this->dropIndex('idx-subscription_payment-partner_payout_uuid', 'subscription_payment');

      $this->dropForeignKey('fk-payment-partner_payout_uuid', 'payment');
      $this->dropIndex('idx-payment-partner_payout_uuid', 'payment');


      $this->alterColumn('partner_payout', 'partner_payout_uuid', $this->char(35));
      $this->alterColumn('payment', 'partner_payout_uuid', $this->char(35));
      $this->alterColumn('subscription_payment', 'partner_payout_uuid', $this->char(35));


            // creates index for column `partner_payout_uuid`
            $this->createIndex(
                    'idx-subscription_payment-partner_payout_uuid', 'subscription_payment', 'partner_payout_uuid'
            );

            // add foreign key for table `subscription_payment`
            $this->addForeignKey(
                    'fk-subscription_payment-partner_payout_uuid', 'subscription_payment', 'partner_payout_uuid', 'partner_payout', 'partner_payout_uuid', 'SET NULL', 'SET NULL'
            );


            // creates index for column `partner_payout_uuid`
            $this->createIndex(
                    'idx-payment-partner_payout_uuid', 'payment', 'partner_payout_uuid'
            );

            // add foreign key for table `partner_payout`
            $this->addForeignKey(
                    'fk-payment-partner_payout_uuid', 'payment', 'partner_payout_uuid', 'partner_payout', 'partner_payout_uuid', 'SET NULL', 'SET NULL'
            );




          $this->dropForeignKey('fk-partner-bank_id', 'partner');
          $this->dropIndex('idx-partner-bank_id', 'partner');


            // creates index for column `bank_id`
            $this->createIndex(
                    'idx-partner-bank_id',
                    'partner',
                    'bank_id'
            );


            // add foreign key for table `partner`
            $this->addForeignKey(
                    'fk-partner-bank_id',
                    'partner',
                    'bank_id',
                    'bank',
                    'bank_id',
                    'SET NULL',
                    'SET NULL'
            );



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('partner', 'partner_phone_number');
      $this->dropColumn('partner', 'partner_phone_number_country_code');

      $this->dropForeignKey('fk-subscription_payment-partner_payout_uuid', 'subscription_payment');
      $this->dropIndex('idx-subscription_payment-partner_payout_uuid', 'subscription_payment');

      $this->dropForeignKey('fk-payment-partner_payout_uuid', 'payment');
      $this->dropIndex('idx-payment-partner_payout_uuid', 'payment');



      $this->alterColumn('partner_payout', 'partner_payout_uuid', $this->char(60));
      $this->alterColumn('payment', 'partner_payout_uuid', $this->char(60));
      $this->alterColumn('subscription_payment', 'partner_payout_uuid', $this->char(60));


            // creates index for column `partner_payout_uuid`
            $this->createIndex(
                    'idx-subscription_payment-partner_payout_uuid', 'subscription_payment', 'partner_payout_uuid'
            );

            // add foreign key for table `subscription_payment`
            $this->addForeignKey(
                    'fk-subscription_payment-partner_payout_uuid', 'subscription_payment', 'partner_payout_uuid', 'partner_payout', 'partner_payout_uuid', 'SET NULL', 'SET NULL'
            );


            // creates index for column `partner_payout_uuid`
            $this->createIndex(
                    'idx-payment-partner_payout_uuid', 'payment', 'partner_payout_uuid'
            );

            // add foreign key for table `partner_payout`
            $this->addForeignKey(
                    'fk-payment-partner_payout_uuid', 'payment', 'partner_payout_uuid', 'partner_payout', 'partner_payout_uuid', 'SET NULL', 'SET NULL'
            );


    }

}
