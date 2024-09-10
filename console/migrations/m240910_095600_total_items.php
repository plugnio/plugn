<?php

use yii\db\Migration;

/**
 * Class m240910_095600_total_items
 */
class m240910_095600_total_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("restaurant", "total_items", $this->integer(11)->defaultValue(0));

        $query = \api\models\Restaurant::find()
            ->andWhere(['restaurant.is_deleted' => 0]);

        foreach ($query->batch(100) as $stores) {
            foreach ($stores as $store) {

                \common\models\Restaurant::updateAll([
                    "total_items" => $store->getItems()
                        ->count()
                ], [
                    "restaurant_uuid" => $store->restaurant_uuid
                ]);
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
        echo "m240910_095600_total_items cannot be reverted.\n";

        return false;
    }
    */
}
