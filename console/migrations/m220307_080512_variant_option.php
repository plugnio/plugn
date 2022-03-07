<?php

use yii\db\Migration;

/**
 * Class m220307_080512_variant_option
 */
class m220307_080512_variant_option extends Migration
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

        $this->createTable('{{%item_variant_option}}', [
            'item_variant_option_uuid' => $this->char(60),
            'item_variant_uuid' => $this->char(60)->notNull(),
            'item_uuid' => $this->string(100)->notNull(),
            'option_id' => $this->integer(11)->notNull(),
            'extra_option_id' => $this->integer(11)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey('PK', 'item_variant_option', 'item_variant_option_uuid');

        // creates index for column `item_uuid`
        $this->createIndex(
            'idx-item_variant_option-item_uuid',
            'item_variant_option',
            'item_uuid'
        );

        // add foreign key for table `item_variant_option`
        $this->addForeignKey(
            'fk-item_variant_option-item_uuid',
            'item_variant_option',
            'item_uuid',
            'item',
            'item_uuid',
            'CASCADE'
        );

        // creates index for column `item_variant_uuid`
        $this->createIndex(
            'idx-item_variant_option-item_variant_uuid',
            'item_variant_option',
            'item_variant_uuid'
        );

        // add foreign key for table `item_variant`
        $this->addForeignKey(
            'fk-item_variant_option-item_variant_uuid',
            'item_variant_option',
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
        echo "m220307_080512_variant_option cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220307_080512_variant_option cannot be reverted.\n";

        return false;
    }
    */
}
