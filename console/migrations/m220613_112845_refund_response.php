<?php

use yii\db\Migration;

/**
 * Class m220613_112845_refund_response
 */
class m220613_112845_refund_response extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('refund', 'refund_message', $this->text()->after('refund_status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220613_112845_refund_response cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220613_112845_refund_response cannot be reverted.\n";

        return false;
    }
    */
}
