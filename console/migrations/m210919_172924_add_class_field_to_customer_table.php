<?php

use yii\db\Migration;

/**
 * Class m210919_172924_add_class_field_to_customer_table
 */
class m210919_172924_add_class_field_to_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn ('customer', 'class', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('customer', 'class');
    }
}
