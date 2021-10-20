<?php

use yii\db\Migration;

/**
 * Class m211019_102314_add_compare_at_price_field_to_item_table
 */
class m211019_102314_add_compare_at_price_field_to_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn ('item', 'compare_at_price', $this->decimal(10,3)->unsigned()->null()->after('item_price'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('item', 'compare_at_price');
    }

}
