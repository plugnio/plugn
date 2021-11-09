<?php

use yii\db\Migration;

/**
 * Class m211109_112639_store_currency
 */
class m211109_112639_store_currency extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'store_currency_code', $this->char(3)->after('currency_code'));
        $this->addColumn('order', 'currency_rate', $this->double(15,8)->after('store_currency_code'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211109_112639_store_currency cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211109_112639_store_currency cannot be reverted.\n";

        return false;
    }
    */
}
