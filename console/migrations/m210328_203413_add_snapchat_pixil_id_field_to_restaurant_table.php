<?php

use yii\db\Migration;

/**
 * Class m210328_203413_add_snapchat_pixil_id_field_to_restaurant_table
 */
class m210328_203413_add_snapchat_pixil_id_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'snapchat_pixil_id', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'snapchat_pixil_id');
    }

}
