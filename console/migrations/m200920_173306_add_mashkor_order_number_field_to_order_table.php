<?php

use yii\db\Migration;

/**
 * Class m200920_173306_add_mashkor_order_number_field_to_order_table
 */
class m200920_173306_add_mashkor_order_number_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'mashkor_order_number', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order', 'mashkor_order_number');
    }

}
