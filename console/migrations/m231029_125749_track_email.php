<?php

use yii\db\Migration;

/**
 * Class m231029_125749_track_email
 */
class m231029_125749_track_email extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("vendor_campaign", "no_of_email_opened",
            $this->integer(11)->after("progress")->defaultValue(0));

        $this->addColumn("vendor_campaign", "no_of_email_sent",
            $this->integer(11)->after("no_of_email_opened")->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m231029_125749_track_email cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m231029_125749_track_email cannot be reverted.\n";

        return false;
    }
    */
}
