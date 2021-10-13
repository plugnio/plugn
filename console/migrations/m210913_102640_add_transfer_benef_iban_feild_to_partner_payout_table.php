<?php

use yii\db\Migration;

/**
 * Class m210913_102640_add_transfer_benef_iban_feild_to_partner_payout_table
 */
class m210913_102640_add_transfer_benef_iban_feild_to_partner_payout_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->renameColumn('partner', 'iban','partner_iban');
      $this->alterColumn('partner', 'partner_iban', $this->string(100));

      $this->addColumn('partner_payout', 'transfer_benef_iban', $this->string(100));
      $this->addColumn('partner_payout', 'transfer_benef_name', $this->string());
      $this->addColumn('partner_payout', 'bank_id', $this->integer());


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

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->renameColumn('partner', 'partner_iban','iban');


      $this->dropForeignKey('fk-partner_payout-bank_id', 'partner_payout');
      $this->dropIndex('idx-partner_payout-bank_id', 'partner_payout');

      $this->dropColumn('partner_payout', 'transfer_benef_iban');
      $this->dropColumn('partner_payout', 'bank_id');
      $this->dropColumn('partner_payout', 'transfer_benef_name');
    }

}
