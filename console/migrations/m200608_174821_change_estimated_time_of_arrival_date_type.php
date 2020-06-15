<?php

use yii\db\Migration;

/**
 * Class m200608_174821_change_estimated_time_of_arrival_date_type
 */
class m200608_174821_change_estimated_time_of_arrival_date_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('order', 'estimated_time_of_arrival', $this->dateTime());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('order', 'estimated_time_of_arrival', $this->time());
    }
}
