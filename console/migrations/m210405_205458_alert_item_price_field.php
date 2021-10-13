<?php

use yii\db\Migration;

/**
 * Class m210405_205458_alert_item_price_field
 */
class m210405_205458_alert_item_price_field extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->alterColumn('item', 'item_price', $this->decimal(10,3)->unsigned()->notNull()->defaultValue(0));
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->alterColumn('item', 'item_price', $this->float()->unsigned());
  }
}
