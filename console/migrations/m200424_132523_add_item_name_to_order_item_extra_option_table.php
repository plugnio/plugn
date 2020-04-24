<?php

use yii\db\Migration;

/**
 * Class m200424_132523_add_item_name_to_order_item_extra_option_table
 */
class m200424_132523_add_item_name_to_order_item_extra_option_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order_item', 'item_name_ar', $this->string(255)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order_item', 'order_item_email');
    }

}
