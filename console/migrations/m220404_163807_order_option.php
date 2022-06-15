<?php

use yii\db\Migration;

/**
 * Class m220404_163807_order_option
 */
class m220404_163807_order_option extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn (
            'order_item_extra_option',
            'option_id',
            $this->integer (11)->after('order_item_id')
        );

        $this->addColumn (
            'order_item_extra_option',
            'option_name',
            $this->string ()->after('extra_option_id')
        );

        $this->addColumn (
            'order_item_extra_option',
            'option_name_ar',
            $this->string ()->after('option_name')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220404_163807_order_option cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220404_163807_order_option cannot be reverted.\n";

        return false;
    }
    */
}
