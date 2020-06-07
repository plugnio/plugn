<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%refunded_amount_field_from_order}}`.
 */
class m200607_203849_drop_refunded_amount_field_from_order_table extends Migration
{

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->dropColumn('order','refunded_amount');
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->addColumn('order','refunded_amount', $this->float()->unsigned()->defaultValue(0));
    }

}
