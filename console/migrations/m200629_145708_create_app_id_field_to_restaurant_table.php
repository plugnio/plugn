<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%app_id_field_to_restaurant}}`.
 */
class m200629_145708_create_app_id_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'app_id', $this->string()->after('restaurant_domain'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'app_id');
    }
}
