<?php

use yii\db\Migration;

/**
 * Class m201101_113204_add_has_deployed_field_to_restaurant_table
 */
class m201101_113204_add_has_deployed_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'has_deployed', $this->tinyInteger(1)->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'has_deployed');
    }

}
