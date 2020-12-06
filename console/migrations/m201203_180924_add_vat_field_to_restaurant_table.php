<?php

use yii\db\Migration;

/**
 * Class m201203_180924_add_vat_field_to_restaurant_table
 */
class m201203_180924_add_vat_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'vat', $this->float()->unsigned()->defaultValue(0)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'vat');
    }

}
