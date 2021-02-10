<?php

use yii\db\Migration;

/**
 * Class m210112_104329_add_hide_request_driver_button_field_to_restaurant_table
 */
class m210112_104329_add_hide_request_driver_button_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'hide_request_driver_button', $this->tinyInteger(1)->defaultValue(1)->unsigned());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'hide_request_driver_button');
    }

}
