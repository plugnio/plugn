<?php

use yii\db\Migration;

/**
 * Class m200617_122344_add_platform_fee
 */
class m200617_122344_add_platform_fee extends Migration
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
      $this->dropColumn('restaurant', 'platform_fee');

    }

}
