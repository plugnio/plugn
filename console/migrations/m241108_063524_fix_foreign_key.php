<?php

use yii\db\Migration;

/**
 * Class m241108_063524_fix_foreign_key
 */
class m241108_063524_fix_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey("fk-restaurant_ingredient-restaurant_uuid", "restaurant_ingredient");

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-restaurant_ingredient-restaurant_uuid', 'restaurant_ingredient', 'restaurant_uuid',
            'restaurant', 'restaurant_uuid',"CASCADE", "CASCADE"
        );

        $this->dropForeignKey("fk-restaurant_inventory-item_uuid", "restaurant_inventory");

        // add foreign key for table `item`
        $this->addForeignKey(
            'fk-restaurant_inventory-item_uuid', 'restaurant_inventory', 'item_uuid',
            'item', 'item_uuid', "CASCADE", "CASCADE"
        );

        $this->dropForeignKey("fk-restaurant_inventory-item_variant_uuid", "restaurant_inventory");

        // add foreign key for table `item_variant`
        $this->addForeignKey(
            'fk-restaurant_inventory-item_variant_uuid', 'restaurant_inventory', 'item_variant_uuid', 'item_variant', 'item_variant_uuid',
            "CASCADE", "CASCADE"
        );

        $this->dropForeignKey("fk-restaurant_inventory-restaurant_uuid", "restaurant_inventory");

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-restaurant_inventory-restaurant_uuid', 'restaurant_inventory', 'restaurant_uuid',
            'restaurant', 'restaurant_uuid',
            "CASCADE", "CASCADE"
        );

        $this->dropForeignKey("fk-restaurant_inventory-ingredient_uuid", "restaurant_inventory");

        // add foreign key for table `ingredient_uuid`
        $this->addForeignKey(
            'fk-restaurant_inventory-ingredient_uuid', 'restaurant_inventory',
            'ingredient_uuid', 'restaurant_ingredient', 'ingredient_uuid',
            "CASCADE", "CASCADE"
        );

        $this->dropForeignKey("fk-restock_history-restaurant_uuid", "restock_history");

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-restock_history-restaurant_uuid', 'restock_history', 'restaurant_uuid', 'restaurant', 'restaurant_uuid',
            "CASCADE", "CASCADE"
        );

        $this->dropForeignKey("fk-restock_history-inventory_uuid", "restock_history");

        // add foreign key for table `inventory`
        $this->addForeignKey(
            'fk-restock_history-inventory_uuid', 'restock_history', 'inventory_uuid', 'restaurant_inventory', 'inventory_uuid',
            "CASCADE", "CASCADE"
        );

        $this->dropForeignKey("fk-supplier-restaurant_uuid", "supplier");

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-supplier-restaurant_uuid', 'supplier', 'restaurant_uuid', 'restaurant', "restaurant_uuid",
            "CASCADE", "CASCADE"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241108_063524_fix_foreign_key cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241108_063524_fix_foreign_key cannot be reverted.\n";

        return false;
    }
    */
}
