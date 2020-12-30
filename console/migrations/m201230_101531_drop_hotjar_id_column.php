<?php

use yii\db\Migration;

/**
 * Class m201230_101531_drop_hotjar_id_column
 */
class m201230_101531_drop_hotjar_id_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->dropColumn('restaurant', 'hotjar_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->addColumn('restaurant', 'hotjar_id', $this->string());
    }
}
