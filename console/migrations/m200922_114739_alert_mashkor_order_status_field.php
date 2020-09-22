<?php

use yii\db\Migration;

/**
 * Class m200922_114739_alert_mashkor_order_status_field
 */
class m200922_114739_alert_mashkor_order_status_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->alterColumn('order', 'mashkor_order_status', $this->tinyInteger(1)->unsigned());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->alterColumn('order', 'mashkor_order_status', $this->string());
    }
}
