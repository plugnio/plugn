<?php

use common\models\RestaurantInventory;
use common\models\RestockHistory;
use common\models\Item;
use common\models\ItemVariant;
use yii\db\Migration;

/**
 * Class m241021_201051_to_new_stock
 */
class m241021_201051_to_new_stock extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //foreach item (simple type)

        $itemQuery = Item::find()
            ->andWhere(['item_type' => \common\models\Item::TYPE_SIMPLE])
            ->andWhere(['track_quantity' => true])
            ->batch();

        foreach ($itemQuery as $items) {
            foreach ($items as $item) {
                $inventory = new RestaurantInventory;
                $inventory->restaurant_uuid = $item->restaurant_uuid;
                $inventory->item_uuid = $item->item_uuid;
                $inventory->stock_quantity = $item->stock_qty;
                $inventory->restocked_at = date("Y-m-d", strtotime($item->item_created_at));
                if (!$inventory->save()) {
                    print_r($inventory->errors);
                    die();
                }

                $model = new RestockHistory;
                $model->restaurant_uuid = $item->restaurant_uuid;
                $model->inventory_uuid = $inventory->inventory_uuid;
                $model->restocked_quantity = $item->stock_qty;
                $model->restocked_at = date("Y-m-d", strtotime($item->item_created_at));

                if (!$model->save()) {
                    print_r($model->errors);
                    die();
                }
            }
        }

        //foreach variant type

        $itemVarientQuery = \common\models\ItemVariant::find()
            ->joinWith(['item'])
            ->andWhere(['track_quantity' => true])
            ->batch();

        foreach ($itemVarientQuery as $variants) {
            foreach ($variants as $variant) {
                $inventory = new RestaurantInventory;
                $inventory->restaurant_uuid = $variant->item->restaurant_uuid;
                $inventory->item_variant_uuid = $variant->item_variant_uuid;
                $inventory->stock_quantity = $variant->stock_qty;
                $inventory->restocked_at = date("Y-m-d", strtotime($variant->created_at));
                if (!$inventory->save()) {
                    print_r($inventory->errors);
                    die();
                }

                $model = new RestockHistory;
                $model->restaurant_uuid = $variant->item->restaurant_uuid;
                $model->inventory_uuid = $inventory->inventory_uuid;
                $model->restocked_quantity = $variant->stock_qty;
                $model->restocked_at = date("Y-m-d", strtotime($variant->created_at));

                if (!$model->save()) {
                    print_r($model->errors);
                    die();
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241021_201051_to_new_stock cannot be reverted.\n";

        return false;
    }
    */
}
