<?php

use yii\db\Migration;

/**
 * Class m201225_104344_add_vat_field_to_order_table
 */
class m201225_104344_add_vat_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'tax', $this->float()->defaultValue(0)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order', 'tax');

    }
}
