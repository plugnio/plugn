<?php

use yii\db\Migration;

/**
 * Class m200728_152635_add_description_field_to_voucher_table
 */
class m200728_152635_add_description_field_to_voucher_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('voucher', 'description' , $this->string()->after('code'));
      $this->addColumn('voucher', 'description_ar' , $this->string()->after('description'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('voucher', 'description');
      $this->dropColumn('voucher', 'description_ar');
    }

}
