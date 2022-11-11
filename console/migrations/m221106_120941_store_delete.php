<?php

use yii\db\Migration;

/**
 * Class m221106_120941_store_delete
 */
class m221106_120941_store_delete extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'is_deleted',
            $this->boolean()->defaultValue(false)->after('is_sandbox'));

        $this->addColumn('restaurant', 'is_under_maintenance',
            $this->boolean()->defaultValue(false)->after('is_sandbox'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m221106_120941_store_delete cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221106_120941_store_delete cannot be reverted.\n";

        return false;
    }
    */
}
