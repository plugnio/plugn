<?php

use yii\db\Migration;

/**
 * Class m201110_124222_add_identification_file_backside
 */
class m201110_124222_add_identification_file_backside extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'identification_file_back_side', $this->string());
      $this->addColumn('restaurant', 'identification_file_id_back_side', $this->string());

      $this->renameColumn('restaurant', 'identification_file', 'identification_file_front_side');
      $this->renameColumn('restaurant', 'identification_file_id', 'identification_file_id_front_side');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->renameColumn('restaurant', 'identification_file_front_side', 'identification_file');
      $this->renameColumn('restaurant', 'identification_file_id_front_side', 'identification_file_id');
      $this->dropColumn('restaurant', 'identification_file_back_side');
      $this->dropColumn('restaurant', 'identification_file_id_back_side');
    }

}
