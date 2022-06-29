<?php

use yii\db\Migration;

/**
 * Class m220629_124139_order_table_changes
 */
class m220629_124139_order_table_changes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('order', 'order_instruction',
            $this->string()->after('gift_message')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'order_instruction');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220629_124139_order_table_changes cannot be reverted.\n";

        return false;
    }
    */
}
