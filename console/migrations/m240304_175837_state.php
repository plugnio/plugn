<?php

use yii\db\Migration;

/**
 * Class m240304_175837_state
 */
class m240304_175837_state extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("order", "state_id", $this->integer(11));

        // creates index for column `state_id`
        $this->createIndex(
            'idx-order-state_id', 'order', 'state_id'
        );

        // add foreign key for table `state`
        $this->addForeignKey(
            'fk-order-state_id', 'order', 'state_id', 'state', 'state_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m240304_175837_state cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240304_175837_state cannot be reverted.\n";

        return false;
    }
    */
}
