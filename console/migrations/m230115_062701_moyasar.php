<?php

use yii\db\Migration;

/**
 * Class m230115_062701_moyasar
 */
class m230115_062701_moyasar extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('order', 'payment_method_id', $this->integer(11)->null());

        $method = new \agent\models\PaymentMethod();
        $method->payment_method_name = "Moyasar";
        $method->payment_method_name_ar = "مويسر";
        $method->vat = 0.000;
        $method->source_id = "";
        $method->payment_method_code = "Moyasar";
        $method->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230115_062701_moyasar cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230115_062701_moyasar cannot be reverted.\n";

        return false;
    }
    */
}
