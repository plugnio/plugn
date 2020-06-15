<?php

use yii\db\Migration;

/**
 * Class m200615_113217_add_items_has_been_restocked_field_to_order_table
 */
class m200615_113217_add_items_has_been_restocked_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order','items_has_been_restocked',   $this->boolean()->notNull()->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order','items_has_been_restocked');
    }
}
