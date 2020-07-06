<?php

use yii\db\Migration;

/**
 * Class m200706_171759_alter_lat_lng_fields
 */
class m200706_171759_alter_lat_lng_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('order', 'latitude', $this->decimal(25, 20));
      $this->alterColumn('order', 'longitude', $this->decimal(25, 20));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('order', 'latitude', $this->decimal(9, 9));
      $this->alterColumn('order', 'longitude', $this->decimal(9, 9));
    }

}
