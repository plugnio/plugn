<?php

use yii\db\Migration;

/**
 * Class m210514_192452_add_lat_and_lng_fields_to_business_location_table
 */
class m210514_192452_add_lat_and_lng_fields_to_business_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('business_location', 'address' , $this->string());
      $this->addColumn('business_location', 'latitude' , $this->decimal(9, 6));
      $this->addColumn('business_location', 'longitude' , $this->decimal(9, 6));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('business_location', 'address');
      $this->dropColumn('business_location', 'latitude');
      $this->dropColumn('business_location', 'longitude');
    }

}
