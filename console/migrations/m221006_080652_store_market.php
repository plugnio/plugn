<?php

use yii\db\Migration;

/**
 * Class m221006_080652_store_market
 */
class m221006_080652_store_market extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'is_public', $this->boolean()->after('demand_delivery')->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221006_080652_store_market cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221006_080652_store_market cannot be reverted.\n";

        return false;
    }
    */
}
