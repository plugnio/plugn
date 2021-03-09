<?php

use yii\db\Migration;

/**
 * Class m201110_181718_add_country_code_field_to_customer_table
 */
class m201110_181718_add_country_code_field_to_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('customer', 'country_code', $this->integer(3)->after('customer_phone_number')->defaultValue(965));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('customer', 'country_code');
    }

}
