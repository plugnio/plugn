<?php

use yii\db\Migration;

/**
 * Class m220405_160631_item
 */
class m220405_160631_item extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('item', 'item_meta_title', $this->text()->after('item_description_ar'));
        $this->addColumn('item', 'item_meta_title_ar', $this->text()->after('item_meta_title'));

        $this->addColumn('category', 'category_meta_title', $this->text()->after('subtitle_ar'));
        $this->addColumn('category', 'category_meta_title_ar', $this->text()->after('category_meta_title'));

        $this->addColumn('restaurant', 'meta_title', $this->text()->after('name_ar'));
        $this->addColumn('restaurant', 'meta_title_ar', $this->text()->after('meta_title'));

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%item_variant_image}}', [
            'item_variant_image_uuid' => $this->char(60),
            'item_variant_uuid' => $this->char(60)->notNull(),
            'item_uuid' => $this->string(100)->notNull(),
            'product_file_name' => $this->string()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'item_variant_image', 'item_variant_image_uuid');

        // creates index for column `item_uuid`
        $this->createIndex(
            'idx-item_variant_image-item_uuid',
            'item_variant_image',
            'item_uuid'
        );

        // add foreign key for table `item_variant_image`
        $this->addForeignKey(
            'fk-item_variant_image-item_uuid',
            'item_variant_image',
            'item_uuid',
            'item',
            'item_uuid',
            'CASCADE'
        );

        // creates index for column `item_variant_uuid`
        $this->createIndex(
            'idx-item_variant_image-item_variant_uuid',
            'item_variant_image',
            'item_variant_uuid'
        );

        // add foreign key for table `item_variant_image`
        $this->addForeignKey(
            'fk-item_variant_image-item_variant_uuid',
            'item_variant_image',
            'item_variant_uuid',
            'item_variant',
            'item_variant_uuid',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220405_160631_item cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220405_160631_item cannot be reverted.\n";

        return false;
    }
    */
}
