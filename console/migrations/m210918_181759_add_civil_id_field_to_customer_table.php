<?php

use yii\db\Migration;

/**
 * Class m210918_181759_add_civil_id_field_to_customer_table
 */
class m210918_181759_add_civil_id_field_to_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn ('customer', 'civil_id', $this->string());
      $this->addColumn ('customer', 'section', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('customer', 'civil_id');
      $this->dropColumn('customer', 'section');
    }

}
