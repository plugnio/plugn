<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%show_opening_hours_field_to_restaurant}}`.
 */
class m200707_194719_create_show_opening_hours_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'show_opening_hours', $this->smallInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant', 'show_opening_hours');
    }
}
