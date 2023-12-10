<?php

use yii\db\Migration;

/**
 * Class m231210_075542_voucher
 */
class m231210_075542_voucher extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("voucher", "is_public", $this->boolean()
            ->after("exclude_discounted_items")->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231210_075542_voucher cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231210_075542_voucher cannot be reverted.\n";

        return false;
    }
    */
}
