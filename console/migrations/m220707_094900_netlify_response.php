<?php

use yii\db\Migration;

/**
 * Class m220707_094900_netlify_response
 */
class m220707_094900_netlify_response extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('queue', 'queue_response', $this->text()->after('queue_status'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220707_094900_netlify_response cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220707_094900_netlify_response cannot be reverted.\n";

        return false;
    }
    */
}
