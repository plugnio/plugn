<?php

use yii\db\Migration;

/**
 * Class m201017_184430_add_site_id_for_restaurant_table
 */
class m201017_184430_add_site_id_for_restaurant_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('restaurant', 'site_id' ,  $this->string()); //by default it will be Abandoned checkout
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('restaurant', 'site_id');
    }
}
