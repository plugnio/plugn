<?php

use yii\db\Migration;

/**
 * Class m200513_035540_add_custom_css_field_to_restaurant_table
 */
class m200513_035540_add_custom_css_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'custom_css', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('restaurant', 'custom_css');
    }
}
