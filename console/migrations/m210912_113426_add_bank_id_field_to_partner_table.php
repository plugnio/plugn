<?php

use yii\db\Migration;

/**
 * Class m210912_113426_add_bank_id_field_to_partner_table
 */
class m210912_113426_add_bank_id_field_to_partner_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

          $this->addColumn('partner', 'benef_name', $this->string());
          $this->addColumn('partner', 'bank_id', $this->integer());


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
                    'CASCADE'
            );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

      $this->dropColumn('partner', 'benef_name');


      $this->dropForeignKey('fk-partner-bank_id', 'partner');
      $this->dropIndex('idx-partner-bank_id', 'partner');
      $this->dropColumn('partner', 'bank_id');

    }

}
