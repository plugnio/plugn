<?php

use yii\db\Migration;

/**
 * Class m200413_203425_alert_restaurant_theme
 */
class m200413_203425_alert_restaurant_theme extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('restaurant_theme', 'light', $this->char(60)->defaultValue('#ffffff'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
