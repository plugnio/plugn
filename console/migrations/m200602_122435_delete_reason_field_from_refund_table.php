<?php

use yii\db\Migration;

/**
 * Class m200602_122435_delete_reason_field_from_refund_table
 */
class m200602_122435_delete_reason_field_from_refund_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('refund', 'reason');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->addColumn('refund', 'reason', $this->string()->notNull());
    }
}
