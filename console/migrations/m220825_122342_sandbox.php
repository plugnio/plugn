<?php

use yii\db\Migration;

/**
 * Class m220825_122342_sandbox
 */
class m220825_122342_sandbox extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'is_sandbox', $this->boolean()->defaultValue(false));

        $this->addColumn('restaurant', 'is_sandbox', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220825_122342_sandbox cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220825_122342_sandbox cannot be reverted.\n";

        return false;
    }
    */
}
