<?php

use yii\db\Migration;

/**
 * Class m240602_102307_soft_delete
 */
class m240602_102307_soft_delete extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("area", "is_deleted", $this->boolean()->defaultValue(0));
        $this->addColumn("city", "is_deleted", $this->boolean()->defaultValue(0));
        $this->addColumn("state", "is_deleted", $this->boolean()->defaultValue(0));
        $this->addColumn("country", "is_deleted", $this->boolean()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240602_102307_soft_delete cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240602_102307_soft_delete cannot be reverted.\n";

        return false;
    }
    */
}
