<?php

use yii\db\Migration;

/**
 * Class m230222_062236_invoice_payment
 */
class m230222_062236_invoice_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('invoice_payment', 'is_sandbox', $this->boolean()->after('received_callback')->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230222_062236_invoice_payment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230222_062236_invoice_payment cannot be reverted.\n";

        return false;
    }
    */
}
