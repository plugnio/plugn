<?php

use yii\db\Migration;

/**
 * Class m220307_073438_variant
 */
class m220307_073438_variant extends Migration
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

        $this->createTable('{{%item_variant}}', [
            'item_variant_uuid' => $this->char(60),
            'item_uuid' => $this->string(100)->notNull(),
            'stock_qty' => $this->integer(11),
            'track_quantity' => $this->boolean(),
            'sku' => $this->string(),
            'barcode' => $this->string(),
            'price' => $this->decimal(10, 3),
            'compare_at_price' => $this->decimal(10, 3),
            'weight' => $this->integer(11),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'item_variant', 'item_variant_uuid');

        // creates index for column `item_uuid`
        $this->createIndex(
            'idx-item_variant-item_uuid',
            'item_variant',
            'item_uuid'
        );

        // add foreign key for table `item_variant`
        $this->addForeignKey(
            'fk-item_variant-item_uuid',
            'item_variant',
            'item_uuid',
            'item',
            'item_uuid',
            'CASCADE'
        );

        $this->addColumn('item', 'item_type', $this->tinyInteger(1)
            ->after('sort_number')->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220307_073438_variant cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220307_073438_variant cannot be reverted.\n";

        return false;
    }
    */
}
