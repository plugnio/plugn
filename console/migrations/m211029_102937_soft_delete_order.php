<?php

use yii\db\Migration;

/**
 * Class m211029_102937_soft_delete_order
 */
class m211029_102937_soft_delete_order extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('order', 'is_deleted', $this->tinyInteger(1)->defaultValue(0)->after('order_status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211029_102937_soft_delete_order cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211029_102937_soft_delete_order cannot be reverted.\n";

        return false;
    }
    */
}
