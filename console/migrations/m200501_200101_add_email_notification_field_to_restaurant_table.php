<?php

use yii\db\Migration;

/**
 * Class m200501_200101_add_email_notification_field_to_restaurant_table
 */
class m200501_200101_add_email_notification_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'restaurant_email_notification', $this->smallInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant', 'restaurant_email_notification');
    }
}
