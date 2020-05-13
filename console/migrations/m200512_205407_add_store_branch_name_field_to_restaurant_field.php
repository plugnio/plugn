<?php

use yii\db\Migration;

/**
 * Class m200512_205407_add_store_branch_name_field_to_restaurant_field
 */
class m200512_205407_add_store_branch_name_field_to_restaurant_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'store_branch_name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       $this->dropColumn('restaurant', 'store_branch_name');
    }

}
