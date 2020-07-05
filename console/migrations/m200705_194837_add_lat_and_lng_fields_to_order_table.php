<?php

use yii\db\Migration;

/**
 * Class m200705_194837_add_lat_and_lng_fields_to_order_table
 */
class m200705_194837_add_lat_and_lng_fields_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order','latitude', $this->decimal(9, 9));
      $this->addColumn('order','longitude', $this->decimal(9, 9));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order','latitude');
      $this->dropColumn('order','longitude');
    }

}
