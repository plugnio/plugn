<?php

use yii\db\Migration;

/**
 * Class m200926_174955_alert_discount_amount_field
 */
class m200926_174955_alert_discount_amount_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('voucher', 'discount_amount', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('voucher', 'discount_amount', $this->integer()->notNull());
    }

}
