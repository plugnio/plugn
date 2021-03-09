<?php

use yii\db\Migration;

/**
 * Class m201110_183917_add_customer_phone_country_code_field_to_order_table
 */
class m201110_183917_add_customer_phone_country_code_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'customer_phone_country_code', $this->integer(3)->after('customer_phone_number')->defaultValue(965));
      $this->addColumn('restaurant', 'owner_phone_country_code', $this->integer(3)->after('owner_number')->defaultValue(965));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order', 'customer_phone_country_code');
      $this->dropColumn('restaurant', 'owner_phone_country_code');
    }

}
