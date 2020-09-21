<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%mashkor_api_key_from_restaurant}}`.
 */
class m200921_111016_drop_mashkor_api_key_from_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->dropColumn('restaurant', 'mashkor_api_key');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->addColumn('restaurant', 'mashkor_api_key', $this->string());
    }
}
