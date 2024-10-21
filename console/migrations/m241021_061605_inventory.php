<?php

use yii\db\Migration;

/**
 * Class m241021_061605_inventory
 */
class m241021_061605_inventory extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        //Ingredients

        $this->createTable('{{%restaurant_ingredient}}', [
            'ingredient_uuid' => $this->char(60),
            "restaurant_uuid" => $this->char(60)->notNull(),
            "name" => $this->string()->comment("Name of the ingredient (e.g., \"Wheat Bread\")")->notNull(),
            "stock_quantity" => $this->integer(11)->comment("Total stock of the ingredient available")->defaultValue(0),
            "image_url" => $this->string()->comment("URL pointing to the ingredient’s image")->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_ingredient', 'ingredient_uuid');

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-restaurant_ingredient-restaurant_uuid', 'restaurant_ingredient', 'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-restaurant_ingredient-restaurant_uuid', 'restaurant_ingredient', 'restaurant_uuid', 'restaurant', 'restaurant_uuid'
        );

        //inventory

        $this->createTable('{{%restaurant_inventory}}', [
            'inventory_uuid' => $this->char(60),
            "restaurant_uuid" => $this->char(60)->notNull(),
            "ingredient_uuid" => $this->char(60),
            "item_variant_uuid" => $this->char(60),
            "item_uuid" => $this->char(60),
            "stock_quantity" => $this->integer(11)->comment("Total stock of the item available"),
            'restocked_at' => $this->dateTime()->comment("when the stock was last replenished"),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restaurant_inventory', 'inventory_uuid');

        // creates index for column `item_uuid`
        $this->createIndex(
            'idx-restaurant_inventory-item_uuid', 'restaurant_inventory', 'item_uuid'
        );

        // add foreign key for table `item`
        $this->addForeignKey(
            'fk-restaurant_inventory-item_uuid', 'restaurant_inventory', 'item_uuid', 'item', 'item_uuid'
        );

        // creates index for column `item_variant_uuid`
        $this->createIndex(
            'idx-restaurant_inventory-item_variant_uuid', 'restaurant_inventory', 'item_variant_uuid'
        );

        // add foreign key for table `item_variant`
        $this->addForeignKey(
            'fk-restaurant_inventory-item_variant_uuid', 'restaurant_inventory', 'item_variant_uuid', 'item_variant', 'item_variant_uuid'
        );

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-restaurant_inventory-restaurant_uuid', 'restaurant_inventory', 'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-restaurant_inventory-restaurant_uuid', 'restaurant_inventory', 'restaurant_uuid', 'restaurant', 'restaurant_uuid'
        );

        // creates index for column `ingredient_uuid`
        $this->createIndex(
            'idx-restaurant_inventory-ingredient_uuid', 'restaurant_inventory', 'ingredient_uuid'
        );

        // add foreign key for table `ingredient_uuid`
        $this->addForeignKey(
            'fk-restaurant_inventory-ingredient_uuid', 'restaurant_inventory', 'ingredient_uuid', 'restaurant_ingredient', 'ingredient_uuid'
        );

        //restock_history Table

        $this->createTable('{{%restock_history}}', [
            'history_uuid' => $this->char(60),
            "restaurant_uuid" => $this->char(60)->notNull(),
            "inventory_uuid" => $this->char(60),
            "restocked_quantity" => $this->integer(11)->comment("Quantity restocked"),
            'restocked_at' => $this->dateTime()->comment("when the restock occurred"),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'restock_history', 'history_uuid');

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-restock_history-restaurant_uuid', 'restock_history', 'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-restock_history-restaurant_uuid', 'restock_history', 'restaurant_uuid', 'restaurant', 'restaurant_uuid'
        );

        // creates index for column `inventory_uuid`
        $this->createIndex(
            'idx-restock_history-inventory_uuid', 'restock_history', 'inventory_uuid'
        );

        // add foreign key for table `inventory`
        $this->addForeignKey(
            'fk-restock_history-inventory_uuid', 'restock_history', 'inventory_uuid', 'restaurant_inventory', 'inventory_uuid'
        );

        //supplier

        $this->createTable('{{%supplier}}', [
            'supplier_uuid' => $this->char(60),
            "restaurant_uuid" => $this->char(60)->notNull(),
            "name" => $this->string()->notNull(),
            "contact_info" => $this->string()->comment("Contact information for the supplier (email, phone)"),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'supplier', 'supplier_uuid');

        // creates index for column `restaurant_uuid`
        $this->createIndex(
            'idx-supplier-restaurant_uuid', 'supplier', 'restaurant_uuid'
        );

        // add foreign key for table `restaurant`
        $this->addForeignKey(
            'fk-supplier-restaurant_uuid', 'supplier', 'restaurant_uuid', 'restaurant', "restaurant_uuid"
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241021_061605_inventory cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241021_061605_inventory cannot be reverted.\n";

        return false;
    }
    */
}
