<?php

use yii\db\Migration;

/**
 * Class m200726_120052_add_armada_qr_code_link_field_to_order_table
 */
class m200726_120052_add_armada_qr_code_link_field_to_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('order', 'armada_qr_code_link',  $this->string());
      $this->renameColumn('order', 'tracking_link', 'armada_tracking_link');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('order', 'armada_qr_code_link');
      $this->renameColumn('order', 'armada_tracking_link', 'tracking_link');
    }

}
