<?php

use yii\db\Migration;

/**
 * Class m210127_162342_add_version_field_to_restaurant_table
 */
class m210127_162342_add_version_field_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'version', $this->smallInteger()->defaultValue(1)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'version');
    }

}
