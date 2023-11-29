<?php

use yii\db\Migration;

/**
 * Class m231102_091803_market_order
 */
class m231102_091803_market_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'is_market_order', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231102_091803_market_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231102_091803_market_order cannot be reverted.\n";

        return false;
    }
    */
}
