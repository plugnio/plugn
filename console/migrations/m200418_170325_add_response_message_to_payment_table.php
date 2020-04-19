<?php

use yii\db\Migration;

/**
 * Class m200418_170325_add_response_message_to_payment_table
 */
class m200418_170325_add_response_message_to_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment', 'response_message', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('payment', 'response_message');
    }
}
