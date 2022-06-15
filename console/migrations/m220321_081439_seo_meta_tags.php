<?php

use yii\db\Migration;

/**
 * Class m220321_081439_seo_meta_tags
 */
class m220321_081439_seo_meta_tags extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('item', 'item_meta_description', $this->text()->after('item_description_ar'));
        $this->addColumn('item', 'item_meta_description_ar', $this->text()->after('item_meta_description'));

        $this->addColumn('category', 'category_meta_description', $this->text()->after('subtitle_ar'));
        $this->addColumn('category', 'category_meta_description_ar', $this->text()->after('category_meta_description'));

        $this->addColumn('restaurant', 'meta_description', $this->text()->after('name_ar'));
        $this->addColumn('restaurant', 'meta_description_ar', $this->text()->after('meta_description'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220321_081439_seo_meta_tags cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220321_081439_seo_meta_tags cannot be reverted.\n";

        return false;
    }
    */
}
