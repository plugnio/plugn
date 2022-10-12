<?php

use yii\db\Migration;

/**
 * Class m221012_122745_debugger
 */
class m221012_122745_debugger extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'enable_debugger',
            $this->boolean()->defaultValue(false)->after('is_sandbox'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221012_122745_debugger cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221012_122745_debugger cannot be reverted.\n";

        return false;
    }
    */
}
