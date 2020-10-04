<?php

use yii\db\Migration;

/**
 * Class m201003_210134_alert_support_pick_up_field_to_restaurant_table
 */
class m201003_210134_alert_support_pick_up_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('restaurant', 'support_delivery', $this->tinyInteger(1)->defaultValue(1)->notNull() );
      $this->alterColumn('restaurant', 'support_pick_up', $this->tinyInteger(1)->defaultValue(0)->notNull() );
      $this->alterColumn('restaurant', 'platform_fee',  $this->float()->unsigned()->defaultValue(0.05));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('restaurant', 'support_delivery', $this->tinyInteger(1)->notNull());
      $this->alterColumn('restaurant', 'support_pick_up', $this->tinyInteger(1)->notNull());
      $this->alterColumn('restaurant', 'platform_fee', $this->float()->unsigned()->defaultValue(0));
    }

}
