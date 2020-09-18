<?php

use yii\db\Migration;

/**
 * Class m200918_172409_alert_live_public_key
 */
class m200918_172409_alert_live_public_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('restaurant', 'live_public_key', $this->string()->defaultValue(null));
      $this->alterColumn('restaurant', 'test_public_key', $this->string()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('restaurant', 'live_public_key', $this->string()); 
      $this->alterColumn('restaurant', 'test_public_key', $this->string()); 
    }
}
