<?php

use yii\db\Migration;

/**
 * Class m230919_083119_option_price
 */
class m230919_083119_option_price extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('option', 'option_price', $this->decimal(10, 3)
            ->defaultValue(0)->after('option_name_ar'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230919_083119_option_price cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230919_083119_option_price cannot be reverted.\n";

        return false;
    }
    */
}
