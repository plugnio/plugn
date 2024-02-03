<?php

use yii\db\Migration;

/**
 * Class m240203_070817_protect_order
 */
class m240203_070817_protect_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("order", "ip_address", $this->string(45));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240203_070817_protect_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240203_070817_protect_order cannot be reverted.\n";

        return false;
    }
    */
}
