<?php

use yii\db\Migration;

/**
 * Class m200915_101700_add_live_public_key_field_to_restaurant_table
 */
class m200915_101700_add_live_public_key_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'live_public_key', $this->string()->defaultValue(null));
      $this->addColumn('restaurant', 'test_public_key', $this->string()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'live_public_key');
      $this->dropColumn('restaurant', 'test_public_key'); 
    }
}
