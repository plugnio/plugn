<?php

use yii\db\Migration;

/**
 * Class m200930_073754_add_item_status_field_to_item_table
 */
class m200930_073754_add_item_status_field_to_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('item', 'item_status' ,  $this->tinyInteger(1)->unsigned()->defaultValue(1)); //by default it will be Abandoned checkout
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('item', 'item_status'); 
    }
}
