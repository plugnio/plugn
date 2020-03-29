<?php

use yii\db\Migration;

/**
 * Class m200328_210254_add_column_received_callback_to_payment
 */
class m200328_210254_add_column_received_callback_to_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment', 'received_callback', $this->boolean()->notNull()->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('payment', 'received_callback');
    }
    
}
