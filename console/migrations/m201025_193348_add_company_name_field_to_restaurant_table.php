<?php

use yii\db\Migration;

/**
 * Class m201025_193348_add_company_name_field_to_restaurant_table
 */
class m201025_193348_add_company_name_field_to_restaurant_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
      $this->addColumn('restaurant', 'company_name', $this->string(255));
      $this->addColumn('restaurant', 'is_tap_enable' ,  $this->boolean()->defaultValue(0));
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
      $this->dropColumn('restaurant', 'company_name');
      $this->dropColumn('restaurant', 'is_tap_enable');
  }
}
