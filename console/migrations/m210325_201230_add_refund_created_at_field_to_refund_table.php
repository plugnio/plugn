<?php

use yii\db\Migration;

/**
 * Class m210325_201230_add_refund_created_at_field_to_refund_table
 */
class m210325_201230_add_refund_created_at_field_to_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('refund', 'refund_created_at', $this->dateTime());
        $this->addColumn('refund', 'refund_updated_at', $this->dateTime());
        $this->addColumn('refund', 'refund_reference', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('refund', 'refund_reference');
      $this->dropColumn('refund', 'refund_created_at');
      $this->dropColumn('refund', 'refund_updated_at');

    }

}
