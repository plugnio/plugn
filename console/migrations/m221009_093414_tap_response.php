<?php

use yii\db\Migration;

/**
 * Class m221009_093414_tap_response
 */
class m221009_093414_tap_response extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment_gateway_queue', 'queue_response', $this->text()->after('queue_status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221009_093414_tap_response cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221009_093414_tap_response cannot be reverted.\n";

        return false;
    }
    */
}
