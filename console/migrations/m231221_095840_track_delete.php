<?php

use yii\db\Migration;

/**
 * Class m231221_095840_track_delete
 */
class m231221_095840_track_delete extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agent', 'agent_deleted_at', $this->dateTime()->after('agent_updated_at'));
        $this->addColumn('restaurant', 'restaurant_deleted_at', $this->dateTime()->after('restaurant_updated_at'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231221_095840_track_delete cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231221_095840_track_delete cannot be reverted.\n";

        return false;
    }
    */
}
