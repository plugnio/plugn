<?php

use yii\db\Migration;

/**
 * Class m200708_213959_add_instagram_url_field_to_restaurant_table
 */
class m200708_213959_add_instagram_url_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'instagram_url', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'instagram_url');
    }
}
