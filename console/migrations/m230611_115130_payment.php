<?php

use yii\db\Migration;

/**
 * Class m230611_115130_payment
 */
class m230611_115130_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('subscription_payment', 'currency_code',
            $this->char(3)->defaultValue("KWD")->after("payment_amount_charged"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('subscription_payment', 'currency');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230611_115130_payment cannot be reverted.\n";

        return false;
    }
    */
}
