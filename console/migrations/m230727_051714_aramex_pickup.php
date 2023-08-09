<?php

use yii\db\Migration;

/**
 * Class m230727_051714_aramex_pickup
 */
class m230727_051714_aramex_pickup extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn( "order","aramex_pickup_id", $this->string()->after('aramex_shipment_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230727_051714_aramex_pickup cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230727_051714_aramex_pickup cannot be reverted.\n";

        return false;
    }
    */
}
