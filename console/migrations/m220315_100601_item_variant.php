<?php

use yii\db\Migration;

/**
 * Class m220315_100601_item_variant
 */
class m220315_100601_item_variant extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('item_variant', 'track_quantity');

        $this->addColumn('order_item', 'item_variant_uuid', $this->char(60)->after('item_uuid'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220315_100601_item_variant cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220315_100601_item_variant cannot be reverted.\n";

        return false;
    }
    */
}
