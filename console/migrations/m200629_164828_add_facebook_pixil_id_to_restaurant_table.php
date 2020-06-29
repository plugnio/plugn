<?php

use yii\db\Migration;

/**
 * Class m200629_164828_add_facebook_pixil_id_to_restaurant_table
 */
class m200629_164828_add_facebook_pixil_id_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'google_analytics_id', $this->string());
      $this->addColumn('restaurant', 'facebook_pixil_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'google_analytics_id');
      $this->dropColumn('restaurant', 'facebook_pixil_id');
    }
}
