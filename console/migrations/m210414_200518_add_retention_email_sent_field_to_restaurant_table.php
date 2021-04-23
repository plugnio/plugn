<?php

use yii\db\Migration;

/**
 * Class m210414_200518_add_retention_email_sent_field_to_restaurant_table
 */
class m210414_200518_add_retention_email_sent_field_to_restaurant_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->addColumn('restaurant', 'retention_email_sent' , $this->smallInteger()->defaultValue(0));
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropColumn('restaurant', 'retention_email_sent');
  }
}
