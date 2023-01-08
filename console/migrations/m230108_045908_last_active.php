<?php

use yii\db\Migration;

/**
 * Class m230108_045908_last_active
 */
class m230108_045908_last_active extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('agent', 'last_active_at',
            $this->dateTime()->null()->after('agent_language_pref'));

        $this->addColumn('restaurant', 'last_active_at',
            $this->dateTime()->null());

        $this->addColumn('restaurant', 'last_order_at',
            $this->dateTime()->null());

        $restaurants = \common\models\Restaurant::find()->all();

        foreach ($restaurants as $restaurant)
        {
            $last_order = $restaurant->getOrders()->orderBy('order_created_at')->one();

            if(!$last_order)
                continue;

            $restaurant->last_order_at = $last_order->order_created_at;
            $restaurant->save(false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230108_045908_last_active cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230108_045908_last_active cannot be reverted.\n";

        return false;
    }
    */
}
