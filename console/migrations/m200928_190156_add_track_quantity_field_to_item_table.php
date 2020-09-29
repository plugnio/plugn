<?php

use yii\db\Migration;

/**
 * Class m200928_190156_add_track_quantity_field_to_item_table
 */
class m200928_190156_add_track_quantity_field_to_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addColumn('item', 'track_quantity' ,  $this->boolean()->defaultValue(1)->after('stock_qty'));
      $this->addColumn('item', 'barcode' ,  $this->string()->after('track_quantity'));
      $this->addColumn('item', 'sku' ,  $this->string()->after('track_quantity'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropColumn('item', 'track_quantity');
      $this->dropColumn('item', 'barcode');
      $this->dropColumn('item', 'sku');
    }
}
