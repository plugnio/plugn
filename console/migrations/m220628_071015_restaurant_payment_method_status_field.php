<?php

use yii\db\Migration;

/**
 * Class m220628_071015_restaurant_payment_method_status_field
 */
class m220628_071015_restaurant_payment_method_status_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn ('restaurant_payment_method', 'status',
            $this->tinyInteger(1)->defaultValue (1)->after('payment_method_id')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant_payment_method', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220628_071015_restaurant_payment_method_status_field cannot be reverted.\n";

        return false;
    }
    */
}
