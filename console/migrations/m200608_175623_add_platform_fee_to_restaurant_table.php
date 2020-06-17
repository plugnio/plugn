<?php

use yii\db\Migration;

/**
 * Class m200608_175623_add_platform_fee_to_restaurant_table
 */
class m200608_175623_add_platform_fee_to_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'platform_fee', $this->float()->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant','platform_fee');
    }

}
