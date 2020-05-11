<?php

use yii\db\Migration;

/**
 * Class m200511_003753_add_phone_number_display_field_to_restaurant_table
 */
class m200511_003753_add_phone_number_display_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'phone_number_display', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('restaurant', 'phone_number_display');
    }

}
