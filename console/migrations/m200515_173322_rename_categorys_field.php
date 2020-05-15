<?php

use yii\db\Migration;

/**
 * Class m200515_173322_rename_categorys_field
 */
class m200515_173322_rename_categorys_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('category', 'category_name', 'title');
        $this->renameColumn('category', 'category_name_ar', 'title_ar');
        
        $this->renameColumn('category', 'sub_category_name', 'subtitle');
        $this->renameColumn('category', 'sub_category_name_ar', 'subtitle_ar');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('category', 'title', 'category_name');
        $this->renameColumn('category', 'title_ar', 'category_name_ar');
        
        $this->renameColumn('category', 'subtitle', 'sub_category_name');
        $this->renameColumn('category', 'subtitle_ar', 'sub_category_name_ar');
    }


}
