<?php

use yii\db\Migration;

/**
 * Class m230612_125659_sort_order
 */
class m230612_125659_sort_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('option', 'sort_number', $this->integer(5)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230612_125659_sort_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230612_125659_sort_order cannot be reverted.\n";

        return false;
    }
    */
}
