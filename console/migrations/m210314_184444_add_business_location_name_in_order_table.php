<?php

use yii\db\Migration;

/**
 * Class m210314_184444_add_business_location_name_in_order_table
 */
class m210314_184444_add_business_location_name_in_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'business_location_name', $this->string()->after('country_name_ar'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order', 'business_location_name');
    }

}
