<?php

use yii\db\Migration;

/**
 * Class m200605_162826_add_new_fileds_to_order_table
 */
class m200605_162826_add_new_fileds_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('order', 'order_status', $this->tinyInteger(1)->unsigned()->defaultValue(9)); //by default it will be Abandoned checkout


        $this->addColumn('order','original_total_price', $this->float()->unsigned()->defaultValue(0));

        $this->renameColumn('order', $name, $newName)

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->alterColumn('order', 'order_status', $this->tinyInteger(1)->unsigned()->defaultValue(1));
        echo "m200605_162826_add_new_fileds_to_order_table cannot be reverted.\n";

        return false;
    }
}
