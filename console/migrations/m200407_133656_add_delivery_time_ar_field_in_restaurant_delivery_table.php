<?php

use yii\db\Migration;

/**
 * Class m200407_133656_add_delivery_time_ar_field_in_restaurant_delivery_table
 */
class m200407_133656_add_delivery_time_ar_field_in_restaurant_delivery_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->addColumn('restaurant_delivery', 'delivery_time_ar', $this->integer()->unsigned()->defaultValue(60)->after('delivery_time'));
                
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant_delivery', 'delivery_time_ar');
    }

}
