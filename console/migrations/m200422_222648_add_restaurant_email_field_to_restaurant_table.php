<?php

use yii\db\Migration;

/**
 * Class m200422_222648_add_restaurant_email_field_to_restaurant_table
 */
class m200422_222648_add_restaurant_email_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'restaurant_email', $this->string()->notNull()->after('phone_number'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant', 'restaurant_email');
    }

}
