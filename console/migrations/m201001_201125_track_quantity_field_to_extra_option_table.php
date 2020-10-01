<?php

use yii\db\Migration;

/**
 * Class m201001_201125_track_quantity_field_to_extra_option_table
 */
class m201001_201125_track_quantity_field_to_extra_option_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('extra_option', 'stock_qty' ,  $this->integer()->unsigned()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('extra_option', 'stock_qty');
    }

}
