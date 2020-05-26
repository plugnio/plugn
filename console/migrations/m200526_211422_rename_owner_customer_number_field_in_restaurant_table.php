<?php

use yii\db\Migration;

/**
 * Class m200526_211422_rename_owner_customer_number_field_in_restaurant_table
 */
class m200526_211422_rename_owner_customer_number_field_in_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('restaurant', 'owner_customer_number', 'owner_number');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('restaurant', 'owner_number', 'owner_customer_number');
    }
}
