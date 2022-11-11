<?php

use yii\db\Migration;

/**
 * Class m221111_064623_sandbox
 */
class m221111_064623_sandbox extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('payment', 'is_sandbox',
            $this->boolean()->defaultValue(false));

        $this->addColumn('subscription_payment', 'is_sandbox',
            $this->boolean()->defaultValue(false));

        $this->addColumn('addon_payment', 'is_sandbox',
            $this->boolean()->defaultValue(false)->after('response_message'));

        //\api\models\Restaurant::updateAll(['is_sandbox' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221111_064623_sandbox cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221111_064623_sandbox cannot be reverted.\n";

        return false;
    }
    */
}
