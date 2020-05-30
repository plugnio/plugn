<?php

use yii\db\Migration;

/**
 * Class m200530_172958_increase_item_description_length_field
 */
class m200530_172958_increase_item_description_length_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('item','item_description' , $this->string(2000));
      $this->alterColumn('item','item_description_ar' , $this->string(2000));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('item','item_description' , $this->string(1000));
      $this->alterColumn('item','item_description_ar' , $this->string(1000));
    }

}
