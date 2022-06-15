<?php

use yii\db\Migration;

/**
 * Class m220221_115736_voucher
 */
class m220221_115736_voucher extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('voucher', 'is_deleted', $this->boolean()->after('minimum_order_amount')->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220221_115736_voucher cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220221_115736_voucher cannot be reverted.\n";

        return false;
    }
    */
}
