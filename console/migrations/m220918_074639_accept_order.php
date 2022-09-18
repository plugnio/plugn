<?php

use yii\db\Migration;

/**
 * Class m220918_074639_accept_order
 */
class m220918_074639_accept_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'restaurant',
            'accept_order_247',
            $this->boolean()->defaultValue(false)->after('demand_delivery')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220918_074639_accept_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220918_074639_accept_order cannot be reverted.\n";

        return false;
    }
    */
}
