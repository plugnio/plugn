<?php

use yii\db\Migration;

/**
 * Class m201018_180502_add_sms_sent_field_in_restaurant_table
 */
class m201018_180502_add_sms_sent_field_in_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'sms_sent' , $this->smallInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order', 'sms_sent');
    }

}
