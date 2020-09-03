<?php

use yii\db\Migration;

/**
 * Class m200807_115044_add_category_image_field_to_category_table
 */
class m200807_115044_add_category_image_field_to_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('category', 'category_image' , $this->string()->after('subtitle_ar'));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('category', 'category_image');
    }

}
