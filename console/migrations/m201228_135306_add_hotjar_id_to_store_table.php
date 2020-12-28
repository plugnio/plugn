<?php

use yii\db\Migration;

/**
 * Class m201228_135306_add_hotjar_id_to_store_table
 */
class m201228_135306_add_hotjar_id_to_store_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'hotjar_id', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'hotjar_id');

    }
}
