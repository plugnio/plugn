<?php

use yii\db\Migration;

/**
 * Class m210502_220325_alert_primary_field_restaurant_theme_table
 */
class m210502_220325_alert_primary_field_restaurant_theme_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('restaurant_theme', 'primary', $this->char(60)->defaultValue('#2B546A'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

}
