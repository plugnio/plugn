<?php

use yii\db\Migration;

/**
 * Class m211205_114342_alert_discount_amount_field
 */
class m211205_114342_alert_discount_amount_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('voucher', 'discount_amount', $this->float()->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('voucher', 'discount_amount', $this->integer()->notNull());
    }
}
