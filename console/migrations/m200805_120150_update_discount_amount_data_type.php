<?php

use yii\db\Migration;

/**
 * Class m200805_120150_update_discount_amount_data_type
 */
class m200805_120150_update_discount_amount_data_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('voucher', 'discount_amount', $this->float()->unsigned()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('voucher', 'discount_amount', $this->integer()->notNull());
    }
}
