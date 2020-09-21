<?php

use yii\db\Migration;

/**
 * Class m200914_164529_add_mashkor_api_key_field_to_restaurant_table
 */
class m200914_164529_add_mashkor_api_key_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'mashkor_api_key', $this->string());
      $this->addColumn('restaurant', 'mashkor_branch_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'mashkor_api_key');
      $this->dropColumn('restaurant', 'mashkor_branch_id');

    }
}
