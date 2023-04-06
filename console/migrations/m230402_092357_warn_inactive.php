<?php

use yii\db\Migration;

/**
 * Class m230402_092357_warn_inactive
 */
class m230402_092357_warn_inactive extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'warned_delete_at', $this->date()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230402_092357_warn_inactive cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230402_092357_warn_inactive cannot be reverted.\n";

        return false;
    }
    */
}
