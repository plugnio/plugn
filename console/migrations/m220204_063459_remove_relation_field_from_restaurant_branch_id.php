<?php

use yii\db\Migration;

/**
 * Class m220204_063459_remove_relation_field_from_restaurant_branch_id
 */
class m220204_063459_remove_relation_field_from_restaurant_branch_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // can't delete this reason being its being used at multiple location and i am not still not clear that why and
        // how we are using this. even with business location its being used.
        $this->dropForeignKey('fk-order-restaurant_branch_id', 'order');
        $this->dropIndex('idx-order-restaurant_branch_id', 'order');
        $this->alterColumn('order','restaurant_branch_id',$this->integer()->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addForeignKey('fk-order-restaurant_branch_id', 'order', 'restaurant_branch_id', 'restaurant_branch', 'restaurant_branch_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220204_063459_remove_relation_field_from_restaurant_branch_id cannot be reverted.\n";

        return false;
    }
    */
}
