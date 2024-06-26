<?php

use yii\db\Migration;

/**
 * Class m240626_062318_fix_country
 */
class m240626_062318_fix_country extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = $this
            ->getDb()
            ->getSchema()
            ->getTableSchema('country');

        if (!isset($table->columns['is_deleted'])) {
            $this->addColumn("country", "is_deleted", $this->boolean()->defaultValue(false));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240626_062318_fix_country cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240626_062318_fix_country cannot be reverted.\n";

        return false;
    }
    */
}
