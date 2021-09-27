<?php

use yii\db\Migration;

/**
 * Class m210926_093755_fix_partner_payout_foreign_key
 */
class m210926_093755_fix_partner_payout_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->dropForeignKey('fk-partner_payout-bank_id', 'partner_payout');
        $this->dropIndex('idx-partner_payout-bank_id', 'partner_payout');



          // creates index for column `bank_id`
          $this->createIndex(
                  'idx-partner_payout-bank_id',
                  'partner_payout',
                  'bank_id'
          );


          // add foreign key for table `partner_payout`
          $this->addForeignKey(
                  'fk-partner_payout-bank_id',
                  'partner_payout',
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
      $this->dropForeignKey('fk-partner-bank_id', 'partner');
      $this->dropIndex('idx-partner-bank_id', 'partner');


      // creates index for column `bank_id`
      $this->createIndex(
              'idx-partner_payout-bank_id',
              'partner_payout',
              'bank_id'
      );


      // add foreign key for table `partner_payout`
      $this->addForeignKey(
              'fk-partner_payout-bank_id',
              'partner_payout',
              'bank_id',
              'bank',
              'bank_id',
              'CASCADE'
      );
    }

}
