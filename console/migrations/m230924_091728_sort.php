<?php

use yii\db\Migration;

/**
 * Class m230924_091728_sort
 */
class m230924_091728_sort extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("item_image", "sort_number", $this->integer()->after("product_file_name")
            ->defaultValue(0));

        $this->addColumn("item_variant_image", "sort_number", $this->integer()->after("product_file_name")
            ->defaultValue(0));

        $this->addColumn("item_video", "sort_number", $this->integer()->after("product_file_name")
            ->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("item_image", "sort_order");
        $this->dropColumn("item_variant_image", "sort_order");
        $this->dropColumn("item_video", "sort_order");

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230924_091728_sort cannot be reverted.\n";

        return false;
    }
    */
}
