<?php

use yii\db\Migration;

/**
 * Class m210528_084559_add_armada_api_key_field_to_business_location_table
 */
class m210528_084559_add_armada_api_key_field_to_business_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('business_location', 'mashkor_branch_id', $this->string());
      $this->addColumn('business_location', 'armada_api_key', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('business_location', 'mashkor_api_key');
      $this->dropColumn('business_location', 'mashkor_branch_id');
    }

}
