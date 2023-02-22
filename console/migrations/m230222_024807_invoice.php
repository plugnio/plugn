<?php

use yii\db\Migration;

/**
 * Class m230222_024807_invoice
 */
class m230222_024807_invoice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant_invoice', 'mail_sent', $this->boolean()->after('currency_code')->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230222_024807_invoice cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230222_024807_invoice cannot be reverted.\n";

        return false;
    }
    */
}
