<?php

use yii\db\Migration;

/**
 * Class m200503_225551_add_tracking_link_field_to_order_table
 */
class m200503_225551_add_tracking_link_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('order', 'tracking_link', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('order', 'tracking_link');
    }
}
