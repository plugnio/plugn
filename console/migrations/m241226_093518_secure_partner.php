<?php

use yii\db\Migration;

/**
 * Class m241226_093518_secure_partner
 */
class m241226_093518_secure_partner extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("partner", "partner_limit_email", $this->dateTime()->null());
        $this->addColumn("partner", "partner_deleted_at", $this->dateTime()->null());
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
        echo "m241226_093518_secure_partner cannot be reverted.\n";

        return false;
    }
    */
}
