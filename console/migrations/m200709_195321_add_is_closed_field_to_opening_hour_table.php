<?php

use yii\db\Migration;

/**
 * Class m200709_195321_add_is_closed_field_to_opening_hour_table
 */
class m200709_195321_add_is_closed_field_to_opening_hour_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->addColumn('opening_hour', 'is_closed',  $this->smallInteger()->defaultValue(0));
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropColumn('opening_hour', 'is_closed');
  }
}
