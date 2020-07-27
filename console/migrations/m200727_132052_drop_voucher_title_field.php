<?php

use yii\db\Migration;

/**
 * Class m200727_132052_drop_voucher_title_field
 */
class m200727_132052_drop_voucher_title_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->dropColumn('voucher', 'title');
      $this->dropColumn('voucher', 'title_ar');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->addColumn('voucher', 'title' , $this->string()->notNull());
      $this->addColumn('voucher', 'title_ar' , $this->string()->notNull());
    }
}
