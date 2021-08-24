<?php

use yii\db\Migration;

/**
 * Class m210816_135804_report
 */
class m210816_135804_report extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->getTableSchema('{{%order_item}}')->getColumn('restaurant_uuid') !== null) {
            return true;
        }

        $this->addColumn ('order_item', 'restaurant_uuid', $this->char (60)->after ('order_uuid'));

        // creates index for column `restaurant_uuid`in table `order_item`
        $this->createIndex(
            'idx-order_item-restaurant_uuid',
            'order_item',
            'restaurant_uuid'
        );

        // add foreign key for `restaurant_uuid` in table `order_item`
        $this->addForeignKey(
            'fk-order_item-restaurant_uuid',
            'order_item',
            'restaurant_uuid',
            'restaurant',
            'restaurant_uuid',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210816_135803_report cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210816_135803_report cannot be reverted.\n";

        return false;
    }
    */
}
