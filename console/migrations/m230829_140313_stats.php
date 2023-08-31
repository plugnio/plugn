<?php

use yii\db\Migration;

/**
 * Class m230829_140313_stats
 */
class m230829_140313_stats extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('restaurant', 'total_orders', $this->integer(11)
            ->unsigned()
            ->defaultValue(0)
            ->after('last_order_at'));

        $query = \common\models\Restaurant::find();

        foreach ($query->batch(100) as $stores) {
            foreach ($stores as $store) {
                $store->total_orders = $store->getOrders()
                    ->checkoutCompleted()
                    ->count();
                $store->save(false);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230829_140313_stats cannot be reverted.\n";

        return false;
    }
    */
}
