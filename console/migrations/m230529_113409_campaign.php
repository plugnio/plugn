<?php

use yii\db\Migration;

/**
 * Class m230529_113409_campaign
 */
class m230529_113409_campaign extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('campaign', 'investment', $this->decimal(10, 3)->after('utm_term'));

        $this->addColumn('campaign', 'no_of_stores', $this->integer(11)
            ->comment("Number of stores created")
            ->after('investment'));

        $this->addColumn('campaign', 'no_of_orders', $this->integer(11)
            ->comment("Total Orders made by those stores")
            ->after('no_of_stores'));

        $this->addColumn('campaign', 'total_commission', $this->decimal(10, 3)
            ->comment("Total Commission made by us from those stores till now")
            ->after('no_of_orders'));

        $this->addColumn('campaign', 'total_gateway_fee', $this->decimal(10, 3)
            ->comment("Total Payment gateway commission from that store till now")
            ->after('total_commission'));


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230529_113409_campaign cannot be reverted.\n";

        return false;
    }
    */
}
