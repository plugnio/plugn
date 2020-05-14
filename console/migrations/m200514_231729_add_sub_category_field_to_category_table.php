<?php

use yii\db\Migration;

/**
 * Class m200514_231729_add_sub_category_field_to_category_table
 */
class m200514_231729_add_sub_category_field_to_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('category', 'sub_category_name', $this->string()->after('category_name_ar'));
        $this->addColumn('category', 'sub_category_name_ar', $this->string()->after('sub_category_name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('category', 'sub_category_name');
        $this->dropColumn('category', 'sub_category_name_ar');
    }
}
