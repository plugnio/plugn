<?php

use yii\db\Migration;

/**
 * Class m231102_100554_tap_merchant
 */
class m231102_100554_tap_merchant extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("restaurant", "tap_merchant_status", $this->string(255)
            ->after("is_tap_business_active"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231102_100554_tap_merchant cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231102_100554_tap_merchant cannot be reverted.\n";

        return false;
    }
    */
}
