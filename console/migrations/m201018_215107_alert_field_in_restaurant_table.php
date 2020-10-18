<?php

use yii\db\Migration;

/**
 * Class m201018_215107_alert_field_in_restaurant_table
 */
class m201018_215107_alert_field_in_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('restaurant', 'thumbnail_image',  $this->string());
      $this->alterColumn('restaurant', 'logo',  $this->string(255));
      $this->alterColumn('restaurant', 'support_delivery',  $this->tinyInteger(1)->defaultValue(1));
      $this->alterColumn('restaurant', 'support_pick_up',  $this->tinyInteger(1)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('restaurant', 'thumbnail_image',  $this->string()->notNull());
      $this->alterColumn('restaurant', 'logo',  $this->string(255)->notNull());
      $this->alterColumn('restaurant', 'support_delivery',  $this->tinyInteger(1)->notNull());
      $this->alterColumn('restaurant', 'support_pick_up',  $this->tinyInteger(1)->notNull());
    }

}
