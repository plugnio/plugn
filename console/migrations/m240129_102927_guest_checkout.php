<?php

use yii\db\Migration;

/**
 * Class m240129_102927_guest_checkout
 */
class m240129_102927_guest_checkout extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("restaurant", "enable_guest_checkout", $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240129_102927_guest_checkout cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240129_102927_guest_checkout cannot be reverted.\n";

        return false;
    }
    */
}
