<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%store_layout_field_to_restaurant}}`.
 */
class m200521_202607_create_store_layout_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'store_layout', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('restaurant', 'store_layout');
    }
}
