<?php

use yii\db\Migration;

/**
 * Class m210320_100524_add_is_my_fatoora_enable_field_to_restaurant_table
 */
class m210320_100524_add_is_my_fatoora_enable_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'is_myfatoorah_enable' ,  $this->boolean()->defaultValue(0)->after('is_tap_enable'));
      $this->addColumn('restaurant', 'supplierCode' ,  $this->boolean()->defaultValue(0)->after('is_myfatoorah_enable'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'is_myfatoorah_enable');
      $this->dropColumn('restaurant', 'supplierCode');
    }

}
